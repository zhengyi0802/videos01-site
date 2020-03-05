<?php

    require_once '../../videos/configuration.php';
    require_once '../YouPHPTubePlugin.php';
    require_once '../../objects/configuration.php';
    require_once 'Live.php';

    error_log("on_liveRecord_done executed!");

    $p = YouPHPTubePlugin::loadPlugin("Live");

    if($_GET != NULL) {
       error_log("on_liveRecord_done return message with GET method: " . json_encode($_GET));
       $app = $_GET['app'];
       $vlashver = $_GET['flashver'];
       $swfurl = $_GET['swfurl'];
       $tcurl = $_GET['tcurl'];
       $pageurl = $_GET['pageurl'];
       $addr = $_GET['addr'];
       $clientid = $_GET['clientid'];
       $call = $_GET['call'];
       $recorder = $_GET['recorder'];
       $name = $_GET['name'];
       $path = $_GET['path'];
       $redirectUrl = $_GET['redirectUri'];
    }

    if($_POST != NULL) {
       error_log("on_liveRecord_done return message with POST method: " . json_encode($_POST));
       $app = $_POST['app'];
       $vlashver = $_POST['flashver'];
       $swfurl = $_POST['swfurl'];
       $tcurl = $_POST['tcurl'];
       $pageurl = $_POST['pageurl'];
       $addr = $_POST['addr'];
       $clientid = $_POST['clientid'];
       $call = $_POST['call'];
       $recorder = $_POST['recorder'];
       $name = $_POST['name'];
       $path = $_POST['path'];
       $redirectUrl = $_POST['redirectUri'];
    }

    // get URL path
    $filename = str_replace("/var/www/html/record/", "", $path);
    $filename = str_replace(".flv", "", $filename);
    $RecorderServer = $p->getRecorderServer()."/";
    $videoURL = $RecorderServer.$filename.".mp4";
    $path     = "/var/www/html/vod/".$filename.".mp4";
    error_log("video file path is ".$videoURL);
    error_log("video local file path is ".$path);
    // get titlw
    $sql = "SELECT `title` FROM `live_transmitions` WHERE `key` LIKE '".$name."'";
    $res = sqlDAL::readSql($sql);
    $data = sqlDAL::fetchAssoc($res);
    sqlDAL::close($res);
    if ($res != false) {
        $title = $data['title'];
    } else {
        $title = "";
    }
    $title .= "(".str_replace($name."-" ,"", $filename).")";

    // get username, user_id
    $pass = strtok($tcurl,"=");
    $pass = strtok("=");
    $sql = "SELECT `id`, `user` FROM `users` WHERE `password` LIKE '".$pass."'";
    $res = sqlDAL::readSql($sql);
    $data = sqlDAL::fetchAssoc($res);
    sqlDAL::close($res);
    if ($res != false) {
        $users_id = $data['id'];
        $username = $data['user'];
    } else {
        $title = "";
    }

    $categories_id = 1;

    $postdata = array(
              'streamer'      => $global['webSiteRootURL'],
              'users_id'      => $users_id,
              'username'      => $username,
              'name'          => $name,
              'videoURL'      => $videoURL,
              'path'          => $path,
              'title'         => $title,
              'categories_id' => $categories_id
    );

    $opts = array(
             'http'=>array(
               'method'=>"POST",
               'content' => http_build_query($postdata)
           )
    );

    $conf = new Configuration();
    $conf->load();
    $url = $conf->getEncoderURL()."liveRecorderEncode.php";
    error_log("on_liveRecord_done.php encoder url = " . $url);
    $context = stream_context_create($opts);

    $result = file_get_contents($url, false, $context);

    error_log("on_liveRecord_done.php done!");
?>
