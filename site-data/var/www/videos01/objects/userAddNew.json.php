<?php
header('Content-Type: application/json');
if (empty($global['systemRootPath'])) {
    $global['systemRootPath'] = '../';
}
require_once $global['systemRootPath'] . 'videos/configuration.php';
require_once $global['systemRootPath'] . 'objects/user.php';
if (!User::isAdmin()) {
    die('{"error":"'.__("Permission denied").'"}');
}
if(!empty($advancedCustomUser->forceLoginToBeTheEmail)){
    $_POST['email'] = $_POST['user'];
}

$user = new User(@$_POST['id']);
$user->setUser($_POST['user']);
$user->setPassword($_POST['pass']);
$user->setEmail($_POST['email']);
$user->setName($_POST['name']);
$user->setIsAdmin($_POST['isAdmin']);
$user->setCanStream($_POST['canStream']);
$user->setCanUpload($_POST['canUpload']);
$user->setCanViewChart($_POST['canViewChart']);
$user->setStatus($_POST['status']);
$user->setEmailVerified($_POST['isEmailVerified']);
$user->setAnalyticsCode($_POST['analyticsCode']);
$unique = $user->setChannelName($_POST['channelName']);

//identify what variables come from external plugins
$userOptions=YouPHPTubePlugin::getPluginUserOptions();
if(is_array($userOptions))
{
    $externalOptions=array();
    foreach($userOptions as $uo => $id)
    {
        if(isset($_POST[$id]))
        $externalOptions[$id]=$_POST[$id];
    }
    $user->setExternalOptions($externalOptions);
}
//save it



if(!empty($_POST['channelName']) && !$unique){
    echo '{"error":"'.__("Channel name already exists").'"}';
    exit;
}
$user->setUserGroups(@$_POST['userGroups']);
echo '{"status":"'.$user->save(true).'"}';
