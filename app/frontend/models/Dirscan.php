<?php

namespace frontend\models;

use yii\db\ActiveRecord;

class Dirscan extends ActiveRecord
{
    public static function tableName()
    {
        return 'tasks';
    }

    public static function dirscan($input)
    {
        
        function is_valid_domain_name($domain_name){
        return (preg_match("/^([a-z-\d\.]*.)([a-z\d](-*[a-z\d])*)(\.([a-z\d](-*[a-z\d])*))(:[0-9]*)*$/i", $domain_name) //valid chars check
                && preg_match("/^.{1,253}$/", $domain_name) //overall length check
                && preg_match("/^[^\.]{1,63}(\.[^\.]{1,63})*$/", $domain_name)   ); //length of each label
        }

        $url = $input["url"];

        $url = rtrim($url, '/');
        $url = rtrim($url, '/');

        $url = ltrim($url, ' ');
        $url = rtrim($url, ' ');

        $url = str_replace(",", " ", $url);
        $url = str_replace("\r", " ", $url);
        $url = str_replace("\n", " ", $url);
        $url = str_replace("|", " ", $url);
        $url = str_replace("&", " ", $url);
        $url = str_replace("&&", " ", $url);
        $url = str_replace(">", " ", $url);
        $url = str_replace("<", " ", $url);
        $url = str_replace("'", " ", $url);
        $url = str_replace("\"", " ", $url);
        $url = str_replace("\\", " ", $url);

        $url = strtolower($url);

        $taskid = $input["taskid"];

        $dirscan = Tasks::find()
            ->where(['taskid' => $taskid])
            ->limit(1)
            ->one();

        $randomid = $dirscan->taskid;

        if (strpos($url, 'https://') !== false) {
                $scheme = "https://";
                $url = str_replace("https://", "", $url);
            } else {
                $scheme = "http://";
                $url = str_replace("http://", "", $url);
        }

        $url = rtrim($url, ' ');
        $url = rtrim($url, '/');

        if (!isset($input["ip"])) {
            $start_dirscan = "sudo mkdir /ffuf/" . $randomid . "/ && sudo docker run --rm --network=docker_default -v ffuf:/ffuf -v configs:/configs/ 5631/ffuf -u " . escapeshellarg($scheme.$url."/FUZZ") . " -t 4 -p 1-2 -mc all -w /configs/dict.txt -H 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.122 Safari/537.36' -r -ac -D -e log,php,asp,aspx,jsp,py,txt,conf,config,bak,backup,swp,old,db,sql -o /ffuf/" . $randomid . "/" . $randomid . ".json -od /ffuf/" . $randomid . "/ -of json";
        }

        if (isset($input["ip"])) {

            $ip = $input["ip"];
            $ip = ltrim($ip, ' ');
            $ip = rtrim($ip, ' ');
            $ip = rtrim($ip, '/');

            $start_dirscan = "sudo mkdir /ffuf/" . $randomid . " && sudo docker run --rm --network=docker_default -v ffuf:/ffuf -v configs:/configs/ 5631/ffuf -u " . escapeshellarg($scheme.$ip."/FUZZ") . " -t 2 -s -p 1-2 -H " . escapeshellarg('Host: ' . $url) . " -H 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.122 Safari/537.36' -mc all -w /configs/dict.txt -r -ac -D -e log,php,asp,aspx,jsp,py,txt,conf,config,bak,backup,swp,old,db,sql -o /ffuf/" . $randomid . "/" . $randomid . ".json -od /ffuf/" . $randomid . "/ -of json ";
        }

        exec($start_dirscan); 

        if (is_valid_domain_name($url)){
            $wayback_result = array();
            $string = array();
            
            exec("curl \"http://web.archive.org/cdx/search/cdx?url=". "*." . $url . "/*" . "&output=list&fl=original&collapse=urlkey\"", $wayback_result);

            foreach ($wayback_result as $id => $result) {
                //wayback saves too much (js,images,xss payloads)
                if(preg_match("/(icons|image|img|images|css|fonts|font-icons|robots.txt|.png|.jpeg|.jpg|.js|%22|\"|\">|<|<\/|\<\/|%20| |%0d%0a)/i", $result) === 1 ){
                    unset($wayback_result[$id]);
                    continue;
                }
            }

            //commoncrawl 
            $crawlurl = json_decode(shell_exec("curl http://index.commoncrawl.org/collinfo.json"), true);
            
            $commoncrawl = shell_exec("curl '" . $crawlurl[0]["cdx-api"] . "?output=json&url=*." . $url . "/*'");

            $commoncrawl = str_replace("}
{", "},{", $commoncrawl);

            $commoncrawl = '[' . $commoncrawl . ']';

            $commoncrawl = json_decode($commoncrawl, true);

            foreach ($commoncrawl as $id) {

                if ($id["status"] == 200 || $id["status"] == 204){
                    if(preg_match("/(icons|image|img|images|css|fonts|font-icons|robots.txt|.png|.jpeg|.jpg|.js|%22|\"|\">|<|<\/|\<\/|%20| |%0d%0a|mp4|webm|svg)/i", $id["url"]) === 1 ){
                        continue;
                    } else $wayback_result[] = $id["url"];
                } 
                    
            }
            
        } else $wayback_result[] = "Wrong domain.".$url;
        
        //Get dirscan results file from volume
        if (file_exists("/ffuf/" . $randomid . "/" . $randomid . ".json")) {
            $output = file_get_contents("/ffuf/" . $randomid . "/" . $randomid . ".json");
            $output = json_decode($output, true);

            $outputarray = array();
            $id=0;
            $result_length = array();

            foreach ($output["results"] as $results) {
                if ($results["length"] >= 0 && !in_array($results["length"], $result_length)){
                    $id++;
                    $result_length[] = $results["length"];//so no duplicates gonna be added
                    $outputarray[$id]["url"] = $results["url"];
                    $outputarray[$id]["length"] = $results["length"];
                    $outputarray[$id]["status"] = $results["status"];
                    $outputarray[$id]["redirect"] = $results["redirectlocation"];

                    if ($results["length"] < 650000 ){
                        exec("sudo chmod -R 755 /ffuf/" . $randomid . "/");

                        $outputarray[$id]["resultfile"] = base64_encode(file_get_contents("/ffuf/" . $randomid . "/" . $results["resultfile"] . ""));
                        }
                    }
                }
            $outputarray = json_encode($outputarray);
        } else $outputarray = "No file.";

        $wayback_result = array_unique($wayback_result);
        $wayback_result = array_map('htmlentities', $wayback_result);
        $wayback_result = json_encode($wayback_result, JSON_UNESCAPED_UNICODE);

        $date_end = date("Y-m-d H-i-s");

        $dirscan->dirscan_status = "Done.";
        $dirscan->dirscan = $outputarray;
        $dirscan->wayback = $wayback_result;
        $dirscan->date = $date_end;

        $dirscan->save();

        //Scaner's work is done -> decrement scaner's amount in DB
        $decrement = ToolsAmount::find()
            ->where(['id' => 1])
            ->one();

        $value = $decrement->dirscan;
        
        if ($value <= 1) {
            $value=0;
        } else $value = $value-1;

        $decrement->dirscan=$value;
        $decrement->save();

        exec("sudo rm -r /ffuf/" . $randomid . "/");
        return 1;

    }

}

