<?php

namespace frontend\models;

use yii\db\ActiveRecord;

class Vhost extends ActiveRecord
{
    public static function tableName()
    {
        return 'tasks';
    }

    public static function vhostscan($input)
    {
        function ipCheck($IP, $CIDR){
            
            list ($net, $mask) = explode("/", $CIDR);

            $ip_net = ip2long($net);
            $ip_mask = ~((1 << (32 - $mask)) - 1);

            $ip_ip = ip2long($IP);

            $ip_ip_net = $ip_ip & $ip_mask;

            return ($ip_ip_net == $ip_net);
        }

        if ((isset($input["taskid"]) && $input["taskid"] != "") && (isset($input["domain"]) && $input["domain"] != "")
            && (isset($input["port"]) && $input["port"] != "") && (isset($input["ip"]) && $input["ip"] != "")) {

            $port = escapeshellarg($input["port"]);
            $maindomain = escapeshellarg($input["domain"]);
            $ip = escapeshellarg($input["ip"]);

            $taskid = $input["taskid"];

            $task = Tasks::find()
                ->where(['taskid' => $taskid])
                ->limit(1)
                ->one();

            $outputdomain = array();
            $length = array();

            $domainlist = explode("\n", file_get_contents("/configs/vhostwordlist.txt"));

            if ($port == 443 || $port == 8443 || (isset($input["ssl"]) && $input["ssl"] == "1")) {
                $scheme = "https";
            } else $scheme = "http";

            foreach ($domainlist as $domaintoask) {

                $curl_result = shell_exec("curl --insecure -H 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.122 Safari/537.36' -s " . $scheme . "://" . $domaintoask . ":" . $port . " --resolve \"" . $domaintoask . ":" . $port . ":" . $ip . "\" | wc -c ");
                sleep(1);

                $curl_length = strlen(trim($curl_result));

                if ($curl_length > 0 && !in_array($curl_length,$length)) {
                    $newdata = array(
                        'ip' => $ip,
                        'port' => $port,
                        'length' => $curl_length,
                        'domain' => $domaintoask,
                        'body' => base64_encode($curl_result),
                    );
                    $outputdomain[] = $newdata;
                }
                if (!in_array($curl_length,$length)) $length[] = $curl_length;

                $curl = shell_exec("curl --insecure -H 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.122 Safari/537.36' -s " . $scheme . "://" . $domaintoask . "." . $maindomain . ":" . $port . " --resolve \"" . $domaintoask . "." . $maindomain . ":" . $port . ":" . $ip . "\" | wc -c");
                sleep(1);

                if ($curl_length > 0 && !in_array($curl_length,$length)) {
                    $newdata = array(
                        'ip' => $ip,
                        'port' => $port,
                        'length' => trim($curl),
                        'domain' => $domaintoask . "." . $maindomain,
                    );
                    $outputdomain[] = $newdata;
                }
                
                if (!in_array($curl_length,$length)) $length[] = $curl_length;
            }

            $date_end = date("Y-m-d H-i-s");

            $task->vhost_status = "Done.";
            $task->vhost = json_encode($outputdomain);

            $task->date = $date_end;

            $a = "Done";
            $task->save();

            $decrement = ToolsAmount::find()
                ->where(['id' => 1])
                ->one();

            $value = $decrement->vhosts;
                
            if ($value <= 1) {
                $value=0;
            } else $value = $value-1;

            $decrement->vhosts=$value;
            $decrement->save();

            return 1;
        }

        if ((isset($input["taskid"]) && $input["taskid"] != "") && (!isset($input["domain"]))) {

            //Cloudflare ip ranges + private networks - no need to scan
            $masks = array("103.21.244.0/22", "103.22.200.0/22", "103.31.4.0/22", "104.16.0.0/12", "108.162.192.0/18", "131.0.72.0/22",
                "141.101.64.0/18", "162.158.0.0/15", "172.64.0.0/13", "188.114.96.0/20", "190.93.240.0/20", "197.234.240.0/22",
                "173.245.48.0/20", "198.41.128.0/17", "172.16.0.0/12", "192.168.0.0/16", "10.0.0.0/8");

            $taskid = $input["taskid"];

            $task = Tasks::find()
                ->where(['taskid' => $taskid])
                ->limit(1)
                ->one();

            $amassoutput = json_decode($task->amass, true);

            $checkedips = array();
            $outputdomain = array();
            $length = array();

            //$domainlist = explode("\n", file_get_contents("/var/www/soft/vhostwordlist.txt"));
            $domainlist = explode("\n", file_get_contents("/configs/vhostwordlist.txt"));

            $maindomain = $amassoutput[0]["domain"];

            foreach ($amassoutput as $json) {

                if (!in_array($json["name"], $domainlist)) {
                    array_push($domainlist, $json["name"]);
                }
            }

            array_unique($domainlist);

            //No need to scan at 127.0.0.1
            if (($key = array_search("127.0.0.1",$domainlist)) !== false) {
                unset($domainlist[$key]);
            }

            //Get vhost names from amass scan & wordlist file + use only unique ones

            foreach ($amassoutput as $json) {

                foreach ($json["addresses"] as $ip) {

                    if (strpos($ip["ip"], '::') === false) {

                        if (!in_array($ip["ip"], $checkedips)) { //if ip wasnt called earlier - then call it

                            $stop = 0;

                            for ($n = 0; $n < count($masks); $n++) { // if isnt in blocked mask

                                if (((ipCheck($ip["ip"], $masks[$n])) == 1)) {
                                    $stop = 1;
                                    break;
                                } else $stop = 0;
                            }

                            if ($stop == 0) {

                                foreach ($domainlist as $domaintoask) {

                                    //Asks localhost, dev, etc

                                    $curl_result = exec("curl --insecure -H 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.122 Safari/537.36' -s http://" . $domaintoask . " --resolve \"" . $domaintoask . ":80:" . $ip["ip"] . "\"");
                                    sleep(1);

                                    $curl_length = strlen(trim($curl_result));

                                    if ($curl_length > 0 && !in_array($curl_length,$length)) {
                                        $newdata = array(
                                            'ip' => $ip["ip"],
                                            'length' => $curl_length,
                                            'domain' => $domaintoask,
                                            'body' => base64_encode($curl_result),
                                        );
                                        $outputdomain[] = $newdata;
                                    }

                                    if (!in_array($curl_length,$length)) $length[] = $curl_length;

                                    //Asks localhost.domain.com, dev.domain.com, etc

                                    $curl_result = exec("curl --insecure -H 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.122 Safari/537.36' -s http://" . $domaintoask . "." . $maindomain . " --resolve \"" . $domaintoask . "." . $maindomain . ":80:" . $ip["ip"] . "\"");
                                    sleep(1);

                                    $curl_length = strlen(trim($curl_result));

                                    if ($curl_length > 0 && !in_array($curl_length,$length)) {
                                        $newdata = array(
                                            'ip' => $ip["ip"],
                                            'length' => $curl_length,
                                            'domain' => $domaintoask . "." . $maindomain,
                                            'body' => base64_encode($curl_result),
                                        );
                                        $outputdomain[] = $newdata;
                                    }
                                    if (!in_array($curl_length,$length)) $length[] = $curl_length;
                                }
                                $checkedips[] = $ip["ip"];
                            }
                        }
                    }
                }
            }

            $date_end = date("Y-m-d H-i-s");

            $task->vhost_status = "Done.";
            $task->vhost = json_encode($outputdomain);

            $task->date = $date_end;

            $task->save();

            $decrement = ToolsAmount::find()
                ->where(['id' => 1])
                ->one();

            $value = $decrement->vhosts;
                
            if ($value <= 1) {
                $value=0;
            } else $value = $value-1;

            $decrement->vhosts=$value;
            $decrement->save();

            return 1;
        }
    }
}

