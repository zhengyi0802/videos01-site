<?php
global $global, $config;
if(!isset($global['systemRootPath'])){
    require_once '../videos/configuration.php';
}
require_once $global['systemRootPath'].'objects/userGroups.php.php';
header('Content-Type: application/json');
$rows = UserGroups::getAllUsersGroups();
$total = UserGroups::getTotalUsersGroups();
echo '{  "current": '.$_POST['current'].',"rowCount": '.$_POST['rowCount'].', "total": '.$total.', "rows":'. json_encode($categories).'}';
