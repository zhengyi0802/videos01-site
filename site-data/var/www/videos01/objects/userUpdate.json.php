<?php
header('Content-Type: application/json');
global $global, $config;
if(!isset($global['systemRootPath'])){
    require_once '../videos/configuration.php';
}

if(!User::isLogged()){
    die("Is not logged");
}

require_once $global['systemRootPath'] . 'objects/user.php';
$user = new User(0);
$user->loadSelfUser();
$user->setUser($_POST['user']);
$user->setPassword($_POST['pass']);
$user->setEmail($_POST['email']);
$user->setName($_POST['name']);
$user->setAbout($_POST['about']);
$user->setAnalyticsCode($_POST['analyticsCode']);
$user->setDonationLink($_POST['donationLink']);
$unique = $user->setChannelName($_POST['channelName']);
if(!$unique){
    echo '{"error":"'.__("Channel name already exists").'"}';
    exit;
}

if (User::isAdmin() && !empty($_POST['status'])) {
    $user->setStatus($_POST['status']);
}
echo '{"status":"'.$user->save().'"}';
User::updateSessionInfo();
