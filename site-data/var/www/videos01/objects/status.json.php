<?php
header('Access-Control-Allow-Origin: *');
global $global, $config;
if(!isset($global['systemRootPath'])){
    require_once '../videos/configuration.php';
}
require_once $global['systemRootPath'] . 'plugin/MobileManager/MobileManager.php';
require_once 'functions.php';
header('Content-Type: application/json');
$obj = new stdClass();
$obj->max_file_size = get_max_file_size();
$obj->file_upload_max_size = file_upload_max_size();
$obj->videoStorageLimitMinutes = $global['videoStorageLimitMinutes'];
$obj->currentStorageUsage = getSecondsTotalVideosLength();
$obj->webSiteLogo = $config->getLogo();
$obj->webSiteTitle = $config->getWebSiteTitle();
$obj->version = $config->getVersion();
$obj->mobileSreamerVersion = MobileManager::getVersion();
$obj->reportVideoPluginEnabled = YouPHPTubePlugin::isEnabledByName("ReportVideo");
$obj->oauthLogin = array();
$obj->oauthLogin[] = array('type'=>'Facebook', 'status'=> !empty(YouPHPTubePlugin::loadPluginIfEnabled('LoginFacebook')));
$obj->oauthLogin[] = array('type'=>'Google', 'status'=> !empty(YouPHPTubePlugin::loadPluginIfEnabled('LoginGoogle')));
$obj->oauthLogin[] = array('type'=>'Twitter', 'status'=> !empty(YouPHPTubePlugin::loadPluginIfEnabled('LoginTwitter')));
$obj->oauthLogin[] = array('type'=>'LinkedIn', 'status'=> !empty(YouPHPTubePlugin::loadPluginIfEnabled('LoginLinkedin')));

$obj->plugins = array();
$plugins = Plugin::getAllEnabled();
foreach ($plugins as $value) {
    $p = YouPHPTubePlugin::loadPlugin($value['dirName']);
    if (is_object($p)) {
        $info = $p->getMobileInfo();
        if(!empty($info)){
            $obj->plugins[$value['dirName']] = $info;
        }
    }
}

echo json_encode($obj);
