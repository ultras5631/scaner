<?php

use yii\web\JqueryAsset;

$this->title = 'Scan result';

$this->registerJsFile(Yii::$app->request->baseUrl . '/js/scanresult.js', [
    'depends' => [
        JqueryAsset::className()
    ]
]);

$this->registerJsFile('https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js', [
    'depends' => [
        JqueryAsset::className()
    ]
]);

$this->registerJsFile('https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js', [
    'depends' => [
        JqueryAsset::className()
    ]
]);

$this->registerJsFile('https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap.min.js', [
    'depends' => [
        JqueryAsset::className()
    ]
]);

?>

<?php if (!Yii::$app->user->isGuest): ?>

    <?php if (isset($amass)) $amass = json_decode($amass, true); else $amass = "" ?>
    <?php if (isset($dirscan)) $dirscan = json_decode($dirscan, true); else $dirscan = "" ?>
    <?php if (isset($gitscan)) $gitscan = json_decode($gitscan, true); else $gitscan = "" ?>
    <?php if (isset($ipscan)) $ipscan = json_decode($ipscan, true); else $ipscan = "" ?>
    <?php if (isset($vhost)) $vhosts = json_decode($vhost, true); else $vhosts = "" ?>
    <?php if (isset($reverseip)) $reverseip = json_decode($reverseip, true); else $reverseip = "" ?>
    <?php if (isset($wayback)) $wayback= json_decode($wayback, true); else $wayback = "" ?>
    <?php if (isset($subtakeover)) $subtakeover = json_decode($subtakeover, true); else $subtakeover = "" ?>

    <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
    <link rel="stylesheet" href="https://bootswatch.com/3/darkly/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"
            integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
            crossorigin="anonymous"></script>

    <div id="messagesuccess" class="alert alert-success alert-dismissible" role="alert"
         style="top: 80%; right: 1%; position: fixed; width: 250px; text-align: center; display: none; z-index: 5; color: #3c763d !important; background-color: #00bc8c !important; border-color: #00bc8c !important;  ">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
        <b>Scan was successfully created!</b>
    </div>

    <div id="messagefailure" class="alert alert-danger alert-dismissible" role="alert"
         style="top: 80%; right: 1%; position: fixed; width: 250px; text-align: center; display: none; z-index: 5; ">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
        <b>Scan wasn't created, contact us!</b>
    </div>


    <style type="text/css">
        .logo {
            display: none;
        }

        .btn-secondary {
            color: #fff !important;
            background-color: #444 !important;
            border-color: #444 !important;
        }

        .card-footer {
            padding: 0.75rem 1.25rem !important;
            background-color: #444 !important ;
            border-top: 1px solid rgba(0, 0, 0, 0.125) !important;
        }

        .text-muted {
            color: #999 !important;
        }

        .footer {
            display: none;
        }

        .cluster:nth-child(even) {
            border-bottom: 1px solid rgb(68, 68, 68) !important;
            padding: 30px 20px 20px 20px !important;
            overflow-x: auto !important;
            white-space: nowrap !important;
            box-shadow: inset 0px 6px 8px rgb(24, 24, 24) !important;
        }

        .cluster {
            border-bottom: 1px solid rgb(68, 68, 68);
            box-shadow: inset 0px 6px 8px rgb(24, 24, 24);
            padding: 30px 20px 20px 20px;
            overflow-x: auto;
            white-space: nowrap;
        }

        .table > thead > tr > td.warning, .table > tbody > tr > td.warning, .table > tfoot > tr > td.warning, .table > thead > tr > th.warning, .table > tbody > tr > th.warning, .table > tfoot > tr > th.warning, .table > thead > tr.warning > td, .table > tbody > tr.warning > td, .table > tfoot > tr.warning > td, .table > thead > tr.warning > th, .table > tbody > tr.warning > th, .table > tfoot > tr.warning > th {
            background-color: #f39c12 !important;
        }

        .table > thead > tr > td.success, .table > tbody > tr > td.success, .table > tfoot > tr > td.success, .table > thead > tr > th.success, .table > tbody > tr > th.success, .table > tfoot > tr > th.success, .table > thead > tr.success > td, .table > tbody > tr.success > td, .table > tfoot > tr.success > td, .table > thead > tr.success > th, .table > tbody > tr.success > th, .table > tfoot > tr.success > th {
            background-color: #00bc8c !important;
        }

        ::-webkit-scrollbar {
            height: 7px !important;
            background-color: rgba(255, 255, 255, 0) !important;
            margin-top: 4px !important;
        }

        ::-webkit-scrollbar-track,
        ::-webkit-scrollbar-thumb {
            border: 4px solid rgba(255, 255, 255, 0) !important;
            background-clip: padding-box !important;
        }

        ::-webkit-scrollbar-track {
            background-color: #ccc !important;
        }

        ::-webkit-scrollbar-thumb {
            background-color: #212121 !important;
        }

        ::-webkit-scrollbar-thumb:hover {
            border: 3px solid rgba(255, 255, 255, 0) !important;
        }

    </style>

    <nav class="navbar navbar-inverse navbar-fixed-top"
         style="text-align: center; display: inline-block; float: none; width: 100%; background-color: #222222">
        <div class="container-fluid">

            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav"
                    style="text-align: center; display: inline-block; float: none; width: 100%">
                    <a class="navbar-brand" href="/site/profile/"><span class="glyphicon glyphicon-home"></span></a>
                    <?php if ($nmap != ""): ?>
                        <li style="text-align: center;  float: none; display: inline-block;"><a href="#scannedhosts">Nmap
                                Results</a></li> <?php endif; ?>
                    <?php if ($amass != ""): ?>
                        <li style="text-align: center;  float: none; display: inline-block;"><a href="#amass">Amass
                                Results</a></li> <?php endif; ?>
                    <?php if ($aquatone != ""): ?>
                        <li style="text-align: center;  float: none; display: inline-block;"><a href="#aquatone">Aquatone
                                Results</a></li> <?php endif; ?>
                    <?php if ($dirscan != ""): ?>
                        <li style="text-align: center;  float: none; display: inline-block;"><a href="#dirscan">Dirscan
                                Results</a></li> <?php endif; ?>
                    <?php if ($gitscan != ""): ?>
                        <li style="text-align: center;  float: none; display: inline-block;"><a href="#gitscan">Gitscan
                                Results</a></li> <?php endif; ?>
                    <?php if ($ipscan != ""): ?>
                        <li style="text-align: center;  float: none; display: inline-block;"><a href="#ipscancan">Ip
                                scan
                                Results</a></li> <?php endif; ?>
                    <?php if ($vhosts != "" && $vhosts!= "[]"): ?>
                        <li style="text-align: center;  float: none; display: inline-block;"><a href="#vhost-1">Vhost
                                scan
                                Results</a></li> <?php endif; ?>
                    <?php if ($js != ""): ?>
                        <li style="text-align: center;  float: none; display: inline-block;"><a href="#jsscan">JS link
                                scan
                                Results</a></li> <?php endif; ?>
                    <?php if ($reverseip != ""): ?>
                        <li style="text-align: center;  float: none; display: inline-block;"><a href="#reverseip">Reverse
                                IP
                                scan
                                Results</a></li> <?php endif; ?>
                    <?php if ($wayback != ""): ?>
                        <li style="text-align: center;  float: none; display: inline-block;"><a href="#wayback">Wayback
                                Results</a></li> <?php endif; ?>
                    <?php if ($subtakeover != ""): ?>
                        <li style="text-align: center;  float: none; display: inline-block;"><a href="#subtakeover">Subtakeover
                                Results</a></li> <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <?php if ($nmap != ""): ?>
        <?php echo $nmap ?>
    <?php endif; ?>

    <?php if ($amass != ""): ?>

        <h3 style="text-align:center; color: rgb(68, 68, 68);" id="amass">Amass output</h3>

        <div class="panel panel-default">
            <div class="panel-body">
                <div class="table-responsive">
                    <table id="table-amass" class="table table-bordered" style="border-collapse: collapse">
                        <thead>
                        <tr>
                            <th style="text-align:center">
                                <b>Host</b>
                            </th>
                            <th style="text-align:center">
                                <b>Ip</b>
                            </th>
                            <th style="text-align:center">
                                <b>Action</b>
                            </th>
                        </tr>
                        </thead>


                        <?php foreach ($amass as $json) {
                            ?>
                            <tr style="text-align: center" valign="middle">
                                <td align="center" style="text-align: center" valign="middle" width="35%">
                                    <a style="vertical-align: middle;"
                                       href="http://<?php echo $json["name"]; ?>" rel="noreferrer"><?php echo $json["name"]; ?></a>
                                </td>
                                <td align="center" style="text-align: center" valign="middle" width="35%">
                                    <p style="vertical-align: middle;"><?php foreach ($json["addresses"] as $ip) {

                                            if (strpos($ip["ip"], '::') == false) {

                                                echo $ip['ip'];
                                                echo " ";
                                            }

                                        } ?>
                                    </p>
                                </td>
                                <td align="center" style="text-align: center" valign="middle" width="35%">

                                    <div class="btn btn-success btn-sm" id="<?php foreach ($json["addresses"] as $ip) {

                                        if (strpos($ip["ip"], '::') == false) {
                                            echo $ip["ip"];
                                            echo ",";
                                        }

                                    } ?>"
                                         onclick="sendnmap(id);">
                                        <b href="#" data-toggle="tooltip" title="Scan this server with Nmap">Nmap</b>
                                    </div>
                                    <div class="btn btn-success btn-sm" id="<?php echo $json["name"] ?>"
                                         onclick="senddirscan(id);">
                                        <b href="#" data-toggle="tooltip" title="Scan this server for config files">Dirscan</b>
                                    </div>

                                </td>
                            </tr>
                            <?php
                        }
                        ?>

                    </table>
                </div>
            </div>
        </div>
<<<<<<< HEAD
=======

        <h3 style="text-align:center; color: rgb(68, 68, 68);"> Google </h3>


        <div style="text-align: center">
            <a style="vertical-align: middle; text-align: center; font-size:25px"
                href="https://groups.google.com/a/<?php echo $amass[0]["domain"]; ?>/forum/#!forumsearch/" rel="noreferrer">https://groups.google.com/a/<?php echo $amass[0]["domain"]; ?>/forum/#!forumsearch/</a>
        </div>
              
        <div style="text-align: center">
            <a style="vertical-align: middle; text-align: center; font-size:25px"
                href="https://sites.google.com/a/<?php echo $amass[0]["domain"]; ?>/sites/system/app/pages/meta/dashboard/categories" rel="noreferrer"> https://sites.google.com/a/<?php echo $amass[0]["domain"]; ?>/sites/system/app/pages/meta/dashboard/categories </a>  
        </div>

        <div style="text-align: center">
            <a style="vertical-align: middle; text-align: center; font-size:25px"
                href='https://github.com/search?p=1&s=indexed&q="<?php echo $amass[0]["domain"] ?>"&type=Code' rel="noreferrer"> https://github.com/search?p=1&q=<?php echo $amass[0]["domain"]; ?>&type=Code&s=indexed </a>  
        </div>

        <div style="text-align: center">
            <a style="vertical-align: middle; text-align: center; font-size:25px"
                href='https://bitbucket.org/<?php echo $amass[0]["domain"]; ?>/profile/projects' rel="noreferrer"> https://bitbucket.org/<?php echo $amass[0]["domain"]; ?>/profile/projects </a>  
        </div>

        <div style="text-align: center">
            <a style="vertical-align: middle; text-align: center; font-size:25px"
                href="https://www.google.com/search?q=-cdn+-help+-support+-static+-img+-image+site%3A.<?php echo $amass[0]["domain"]; ?>+%28ext%3Axml+%7C+ext%3Aconf+%7C+ext%3Acnf+%7C+ext%3Areg+%7C+ext%3Ainf+%7C+ext%3Ardp+%7C+ext%3Acfg+%7C+ext%3Atxt+%7C+ext%3Aora+%7C+ext%3Aini+%7C+ext%3Aphp+%7C+ext%3Aasp%29" rel="noreferrer"> Google extensions </a>  
        </div>

        <div style="text-align: center">
            <a style="vertical-align: middle; text-align: center; font-size:25px"
                href="https://www.google.com/search?q=site:<?php echo $amass[0]["domain"]; ?>+intitle:index.of" rel="noreferrer"> Index Of </a>  
        </div>

        <div style="text-align: center">
            <a style="vertical-align: middle; text-align: center; font-size:25px"
                href="https://www.google.com/search?q=site:ideone.com+%7C+site:codebeautify.org+%7C+site:codeshare.io+%7C+site:codepen.io+%7C+site:repl.it+%7C+site:justpaste.it+%7C+site:pastebin.com+%7C+site:jsfiddle.net+%7C+site:trello.com+%22.<?php echo $amass[0]["domain"]; ?>%22" rel="noreferrer"> IDE/Pastebin 2</a>  
        </div>

        <div style="text-align: center">
            <a style="vertical-align: middle; text-align: center; font-size:25px"
                href="https://storage.googleapis.com/<?php echo $amass[0]["domain"]; ?>" rel="noreferrer"> https://storage.googleapis.com/<?php echo $amass[0]["domain"]; ?> </a>  
        </div>

        <div style="text-align: center">
            <a style="vertical-align: middle; text-align: center; font-size:25px"
                href="https://buckets.grayhatwarfare.com/buckets/0/<?php echo $amass[0]["domain"]; ?>" rel="noreferrer"> Buckets </a>  
        </div>

        <div style="text-align: center">
            <a style="vertical-align: middle; text-align: center; font-size:25px"
                href="https://<?php echo $amass[0]["domain"]; ?>.atlassian.net/servicedesk/customer/user/login" rel="noreferrer"> https://<?php echo $amass[0]["domain"]; ?>.atlassian.net/servicedesk/customer/user/login </a>  
        </div>

>>>>>>> 25872b2... Merge remote-tracking branch 'origin/master'
        <script>

            $(document).ready(function () {
                $('#table-amass').DataTable();
            });

        </script>

    <?php endif; ?>

    <?php if ($aquatone != ""): ?>

        <h3 style="text-align:center; color: rgb(68, 68, 68);" id="aquatone">Aquatone output</h3>

        <div class="text-muted" style="text-align: center; margin-bottom: 1%">

            <b class="btn btn-default" id="send-to-scan" onclick="sendtoscan();" style="color: #ffffff !important; background-color: #464545 !important; border-color: #464545 !important; background-image: none !important;">Scan selected</b>

        </div>

        <?php echo $aquatone; ?>

        <style type="text/css">
            .footer {
                display: none;
            }
        </style>
    <?php endif; ?>

    <?php if ($gitscan != ""): ?>

        <h3 style="text-align:center" id="gitscan">Gitscan output</h3>
        <h4 style="text-align:center"><?php echo $gitscan[0]["repository"] ?></h4>

        <div class="panel panel-default">
            <div class="panel-body">
                <div class="table-responsive">
                    <table id="table-gitscan" class="table table-bordered" style="border-collapse: collapse;">
                        <thead>
                        <tr>
                            <th style="text-align:center">
                                <li align="center" class="list-group-item">
                                    <b>File</b>
                                </li>
                            </th>
                            <th style="text-align:center">
                                <li align="center" class="list-group-item">
                                    <b>String</b>
                                </li>
                            </th>
                        </tr>
                        </thead>


                        <?php foreach ($gitscan[0]["stringsFound"] as $stringfile => $stringoutput) { ?>
                            <tr>

                                <td align="center">
                                    <li align="center" class="list-group-item">
                                        <b><?php echo $stringfile; ?></b>
                                    </li>
                                </td>

                                <?php $array = array_values($stringoutput); ?>
                                <td>
                                    <ul class="list-group">
                                        <?php foreach ($array as $string) { ?>

                                            <li align="center" class="list-group-item">
                                                <b><?php echo $string; ?></b>
                                            </li>

                                        <?php } ?>
                                    </ul>
                                </td>
                            </tr>
                        <?php } ?>
                    </table>
                </div>
            </div>
        </div>
        <script>
            $(document).ready(function () {
                $('#table-gitscan').DataTable();
            });
        </script>

    <?php endif; ?>


    <?php if ($dirscan != ""): ?>

        <style>
            .response-headers-container {
                display: none;
            }

            table.response-headers td {
                font-family: Anonymous Pro, Consolas, Menlo, Monaco, Lucida Console, Liberation Mono, DejaVu Sans Mono, Bitstream Vera Sans Mono, Courier New, monospace, serif;
            }

            table.response-headers tr.insecure td {
                color: #E74C3C;
                font-weight: bold;
            }

            table.response-headers tr.secure td {
                color: rgb(0, 188, 140);
                font-weight: bold;
            }

            .page {
                overflow: hidden;
                box-shadow: unset !important;
            }
        </style>

        <h3 style="text-align:center" id="dirscan">Dirscan output</h3>

        <div class="panel panel-default">
            <div class="panel-body">
                <div class="table-responsive">
                    <table id="table-dirscan" class="table table-bordered" style="border-collapse: collapse;">
                        <thead>
                        <tr>
                            <th style="text-align:center;">
                                <b style="text-align: center">URL</b>
                            </th>

                            <th style="text-align:center">
                                <b style="text-align: center">Redirect</b>
                            </th>

                            <th style="text-align:center">
                                <b style="text-align: center">Size</b>
                            </th>

                            <th style="text-align:center;">
                                <b style="text-align: center">Code</b>
                            </th>

                            <th style="text-align:center;">
                                <b style="text-align: center">Response</b>
                            </th>

                        </tr>
                        </thead>

                        <tbody>
                        <?php foreach ($dirscan as $scan) { ?>
                                <?php if ($scan["status"] != "20") { ?>
                                    <tr>
                                        <td style=" width: 350px;">
                                            <ul class="list-group">
                                                <li align="center" class="list-group-item"
                                                    style="height: 40px; min-height: 40px;">
                                                    <div style="text-align: center; overflow:auto; white-space:nowrap; resize: none; ">
                                                        <a style="vertical-align: middle;"
                                                           href="<?php echo $scan["url"];?>" rel="noreferrer"><?php echo $scan["url"]; ?></a>
                                                    </div>
                                                </li>
                                            </ul>
                                        </td>

                                        <td style=" width: 300px">
                                            <ul class="list-group">
                                                <li align="center" class="list-group-item"
                                                    style="height: 40px; min-height: 40px; max-width: 550px; min-width: 300px; width: 300px;">
                                                    <div style="text-align: center; overflow:auto; white-space:nowrap; resize: none; ">
                                                        <b style="vertical-align: middle;"><?php echo $scan["redirect"]; ?></b>
                                                    </div>
                                                </li>
                                            </ul>
                                        </td>

                                        <td style="width: 100px;">
                                            <ul class="list-group">
                                                <li align="center" class="list-group-item"
                                                    style="height: 40px; min-height: 40px;">
                                                    <div style="text-align: center; overflow:auto; white-space:nowrap; resize: none; ">
                                                        <b style="vertical-align: middle;"><?php echo $scan["length"]; ?></b>
                                                    </div>
                                                </li>
                                            </ul>
                                        </td>

                                        <td style="width: 150px">
                                            <ul class="list-group">
                                                <li align="center" class="list-group-item"
                                                    style="height: 40px; min-height: 40px;">
                                                    <div style="text-align: center; overflow:auto; white-space:nowrap; resize: none; ">
                                                        <b style="vertical-align: middle;"><?php echo $scan["status"]; ?></b>
                                                    </div>
                                                </li>
                                            </ul>
                                        </td>

                                        <td align="center" valign="middle" style="text-align: center;"  width="15%">
                                            <ul class="list-group">
                                                    <div style="text-align: center; overflow:auto; white-space:nowrap; resize: none; ">
                                                        <div class="page card mb-3">

                                                            <div class="card-footer text-muted">
                                                                <a style="vertical-align: middle;" href="#" class="card-link page-details-link" >Response</a>
                                                            </div>

                                                            <div class="response-headers-container">
                                                                <table class="table table-responsive table-striped table-hover table-sm response-headers">
                                                                    <thead class="thead-dark">
                                                                        <tr>
                                                                            <th scope="col">Response</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <tr>   
                                                                            <td style="word-wrap: break-word; max-width: 100px;">
                                                                                <?php echo(nl2br(htmlspecialchars(base64_decode($scan["resultfile"])))); ?>   
                                                                            </td>
                                                                        </tr> 
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                            </ul>
                                        </td>
                                    </tr>

                                <?php } ?>
                            <?php } ?>
                        
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="details_modal">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body"></div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            <script type="text/javascript">

                $(document).ready(function () {
                    $('#table-dirscan').DataTable();
                });

                $(document).ready(function () {
                    $(".page-details-link").on("click", function (e) {
                        e.preventDefault();
                        var page = $(this).closest(".page");
                        var url = page.find("h5.card-title").text();
                        var headers = page.find(".response-headers-container").html();
                        $("#details_modal .modal-header h5").text(url);
                        $("#details_modal .modal-body").html(headers);
                        $("#details_modal").modal();
                    });
                });

            </script>

    <?php endif; ?>

    <?php if ($ipscan != ""): ?>

        <h2 style="text-align:center" id="ipscancan">Ip scan output</h2>

        <h4 style="text-align:center" id="ipscancan">Possible IP's:</h4>

        <div class="panel panel-default">
            <div class="panel-body">
                <div class="table-responsive">
                    <table id="table-gitscan" class="table table-bordered" style="border-collapse: collapse;">
                        <thead>
                        <tr>
                            <th style="text-align:center">
                                <li align="center" class="list-group-item">
                                    <b>IP</b>
                                </li>
                            </th>
                            <th style="text-align:center">
                                <li align="center" class="list-group-item">
                                    <b>Action</b>
                                </li>
                            </th>
                        </tr>
                        </thead>

                        <?php foreach ($ipscan as $ip) { ?>
                            <tr>

                                <td align="center">
                                    <li align="center" class="list-group-item">
                                        <b><?php echo $ip["ip"]; ?></b>
                                    </li>
                                </td>

                                <td align="center" style="text-align: center" valign="middle" width="35%">

                                    <div class="btn btn-success btn-sm" id="<?php

                                    if (strpos($ip["ip"], '::') == false) {
                                        echo $ip["ip"];
                                        echo ",";
                                    }

                                    ?>"
                                         onclick="sendnmap(id);">
                                        <b href="#" data-toggle="tooltip" title="Scan this server with Nmap">Nmap</b>
                                    </div>

                                </td>

                            </tr>
                        <?php } ?>
                    </table>
                </div>
            </div>
        </div>
        <script>
            $(document).ready(function () {
                $('#table-ipscan').DataTable();
            });
        </script>

    <?php endif; ?>

    <?php if ($vhosts != "" && $vhosts!= "[]"): ?>

        <style>
            .response-headers-container {
                display: none;
            }

            table.response-headers td {
                font-family: Anonymous Pro, Consolas, Menlo, Monaco, Lucida Console, Liberation Mono, DejaVu Sans Mono, Bitstream Vera Sans Mono, Courier New, monospace, serif;
            }

            table.response-headers tr.insecure td {
                color: #E74C3C;
                font-weight: bold;
            }

            table.response-headers tr.secure td {
                color: rgb(0, 188, 140);
                font-weight: bold;
            }

            .page {
                overflow: hidden;
                box-shadow: unset !important;
            }
        </style>

            <div id="vhostdiv-1">
            <h3 style="text-align:center; color: rgb(68, 68, 68);" id="vhost-1">Vhost output</h3>

            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="table-responsive">
                        <table id="table-vhost" class="table table-bordered"
                               style="border-collapse: collapse">
                            <thead>
                            <tr>
                                <th style="text-align:center">
                                    <b>IP</b>
                                </th>
                                <th style="text-align:center">
                                    <b>Domain</b>
                                </th>
                                <th style="text-align:center">
                                    <b>Length</b>
                                </th>
                                <th style="text-align:center">
                                    <b>Body</b>
                                </th>
                            </tr>
                            </thead>

                            <?php foreach ($vhosts as $vhost) {
                                ?>

                                <tr style="text-align: center" valign="middle">
                                    <td align="center" style="text-align: center;" valign="middle" width="25%">
                                        <b style="vertical-align: middle;"><?php echo($vhost["ip"]); ?> </b>
                                    </td>
                                    <td align="center" style="text-align: center;" valign="middle" width="25%">
                                        <b style="vertical-align: middle;"><?php echo($vhost["domain"]); ?> </b>
                                    </td>
                                    <td align="center" style="text-align: center;" valign="middle" width="25%">
                                        <b style="vertical-align: middle;"><?php echo($vhost["length"]); ?> </b>
                                    </td>
                                    <td align="center" style="text-align: center;" valign="middle" width="15%">
                                        <div class="page card mb-3" style="width: 80%;">

                                            <div class="card-footer text-muted">
                                                <a href="#" class="card-link page-details-link">Response</a>
                                            </div>

                                            <div class="response-headers-container">
                                                <table class="table table-responsive table-striped table-hover table-sm response-headers">
                                                    <thead class="thead-dark">
                                                        <tr>
                                                            <th scope="col">Response</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                            
                                                            <tr>   
                                                                <td style="word-wrap: break-word; max-width: 100px;">
                                                                    <?php echo(nl2br(htmlspecialchars(base64_decode($vhost["body"])))); ?>   
                                                                </td>
                                                            </tr>
                                                            
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                <?php
                            }
                            ?>

                        </table>
                        
                    </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="details_modal">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body"></div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            <script type="text/javascript">

                $(document).ready(function () {
                    $('#table-vhost').DataTable();
                });

                $(document).ready(function () {
                    $(".page-details-link").on("click", function (e) {
                        e.preventDefault();
                        var page = $(this).closest(".page");
                        var url = page.find("h5.card-title").text();
                        var headers = page.find(".response-headers-container").html();
                        $("#details_modal .modal-header h5").text(url);
                        $("#details_modal .modal-body").html(headers);
                        $("#details_modal").modal();
                    });
                });

            </script>

    <?php endif; ?>

    <?php if ($js != ""): ?>

        <h3 style="text-align:center; color: rgb(68, 68, 68);" id="jsscan">JS scan output</h3>

        <?php echo $js ?>

        <script>

            $(document).ready(function () {
                document.body.contentEditable = "false";
            });

        </script>

    <?php endif; ?>

    <?php if ($subtakeover != ""): ?>
        <h3 style="text-align:center; color: rgb(68, 68, 68);" id="subtakeover">Subtakeover output</h3>
        <?php echo $subtakeover ?>
    <?php endif; ?>

    <?php if ($reverseip != ""): ?>
        <h3 style="text-align:center; color: rgb(68, 68, 68);" id="reverseip">Reverse IP scan output</h3>

        <div class="panel panel-default">
            <div class="panel-body">
                <div class="table-responsive">
                    <table id="table-reverseip" class="table table-bordered" style="border-collapse: collapse">
                        <thead>
                        <tr>
                            <th style="text-align:center">
                                <b>Domain</b>
                            </th>
                        </tr>
                        </thead>

                        <?php foreach ($reverseip as $ip) {
                            ?>
                            <tr style="text-align: center" valign="middle">
                                <td align="center" style="text-align: center" valign="middle" width="100%">
                                    <b style="vertical-align: middle;"><?php echo $ip; ?></b>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>

                    </table>
                </div>
            </div>
        </div>
        <script>

            $(document).ready(function () {
                $('#table-reverseip').DataTable();
            });

        </script>

    <?php endif; ?>

    <?php if ($wayback != ""): ?>
        <h3 style="text-align:center; color: rgb(68, 68, 68);" id="wayback">Wayback output</h3>

        <div class="panel panel-default">
            <div class="panel-body">
                <div class="table-responsive">
                    <table id="table-wayback" class="table table-bordered" style="border-collapse: collapse">
                        <thead>
                        <tr>
                            <th style="text-align:center">
                                <b>Link</b>
                            </th>
                        </tr>
                        </thead>

                        <?php foreach ($wayback as $link) {
                            ?>
                                <?php  {

                                    echo '<tr style="text-align: left" valign="middle" width="100%">
                                            <td align="left" style="text-align: left" valign="middle">';

                                    echo ("<a style='vertical-align: middle;' href=" . $link . " rel='noreferrer'>" . $link . "</a>");

                                    echo "</td></tr>";
                                            
                                } ?>
                            <?php
                        }
                        ?>

                    </table>
                </div>
            </div>
        </div>
        <script>

            $(document).ready(function () {
                $('#table-wayback').DataTable();
            });

        </script>

    <?php endif; ?>


    <?php if ($dirscan == "" && $amass == "" && $gitscan == "" && $nmap == "" && $ipscan == "" && $vhosts == "" && $js == "" && $reverseip == "" && $wayback == ""): ?>

        <h1 style="text-align:center; vertical-align: center;">No content yet! Please wait some time for results.</h1>

    <?php endif; ?>

<?php endif; ?>