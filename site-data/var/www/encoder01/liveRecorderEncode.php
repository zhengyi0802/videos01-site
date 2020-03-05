
<?php
    $obj = new stdClass();
    header('Content-Type: application/json');

    require_once("videos/configuration.php");
    require_once $global['systemRootPath'] . 'objects/Encoder.php';
    require_once $global['systemRootPath'] . 'objects/Login.php';
    require_once $global['systemRootPath'] . 'objects/Streamer.php';

    error_log("liveRecorderEncode.php get POST datas : " . json_encode($_POST));

    $streamer = $_POST['streamer'];
    $users_id = $_POST['users_id'];
    $username = $_POST['username'];
    $name     = $_POST['name'];
    $videoURL = $_POST['videoURL'];
    $path     = $_POST['path'];
    $title    = $_POST['title'];
    $categories_id = $_POST['categories_id'];
    $filename = uniqid("{$name}_YPTuniqid_", true) . ".mp4";

    $obj->type = "success";
    $obj->title = "Congratulations!";
    $obj->text = sprintf("Your video (%s) is downloading", $title);
    //error_log("liveRecorderEncode.php : get POST DATA finished!");

    $sql = "SELECT `id` FROM `streamers` WHERE siteURL LIKE '" .$streamer."'";
    error_log("test.php : sql = ". $sql);
    $res = $global['mysqli']->query($sql);
    if ($res) {
        $streamers_id = $res->fetch_assoc();
        if(!$streamers_id) $streamers_id = 1;
    } else {
        error_log("liveRecorderEncode.php : get streamers_id failed!");
        $streamers_id = 1;
    }
    //error_log("liveRecorderEncode.php : streamers_id = ".$streamers_id);

    $s = new Streamer($streamers_id);

    $e = new Encoder("");
    $e->setStreamers_id($streamers_id);
    $e->setTitle($title);
    $e->setFileURI($videoURL);
    $e->setVideoDownloadedLink($videoURL);
    $e->setFilename($filename);
    $e->setStatus('queue');
    $e->setPriority($s->getPriority());
    //$e->setNotifyURL($global['YouPHPTubeURL'] . "youPHPTubeEncoder.json");
    error_log("liveRecorderEncode.php : Encoder initialized!");

    $encoders_ids = array();
    if (!empty($_POST['audioOnly']) && $_POST['audioOnly'] !== 'false') {
        if (!empty($_POST['spectrum']) && $_POST['spectrum'] !== 'false') {
            $e->setFormats_idFromOrder(70); // video to spectrum [(6)MP4 to MP3] -> [(5)MP3 to spectrum] -> [(2)MP4 to webm] 
            error_log("setFormats_idFromOrder(70)");
        } else {
            $e->setFormats_idFromOrder(71);
            error_log("setFormats_idFromOrder(71)");
        }
    } else {
        $formatorder = decideFormatOrder();
        error_log("liveRecorderEncode.php : setFormats_idFromOrder(".$formatorder.")");
        $e->setFormats_idFromOrder(80);
    }

    $obj = new stdClass();
    $f = new Format($e->getFormats_id());
    $format = $f->getExtension();
    error_log("get format = ".$format);
    $response = Encoder::sendFile('', 0, $format, $e);
    //var_dump($response);exit;
    if (!empty($response->response->video_id)) {
        $obj->videos_id = $response->response->video_id;
    }
    $e->setReturn_vars(json_encode($obj));

    $encoders_ids[] = $e->save();

    $e->run();


    error_log("liveRecorderEncode.php done :". json_encode($obj));

?>
