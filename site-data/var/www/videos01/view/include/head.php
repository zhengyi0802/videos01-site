<?php
require_once $global['systemRootPath'] . 'plugin/YouPHPTubePlugin.php';
$head = YouPHPTubePlugin::getHeadCode();
$custom = "The Best YouTube Clone Ever - YouPHPTube";
$extraPluginFile = $global['systemRootPath'] . 'plugin/Customize/Objects/ExtraConfig.php';

$custom = "";

if (!empty($poster)) {
    $subTitle = str_replace(array('"', "\n", "\r"), array("", "", ""), strip_tags($video['description']));
    $custom .= " {$subTitle}";
}

if (!empty($_GET['catName'])) {
    $category = Category::getCategoryByName($_GET['catName']);
    $description = str_replace(array('"', "\n", "\r"), array("", "", ""), strip_tags($category['description']));
    $custom = " {$description} - {$custom}";
}

if (file_exists($extraPluginFile) && YouPHPTubePlugin::isEnabled("c4fe1b83-8f5a-4d1b-b912-172c608bf9e3")) {
    require_once $extraPluginFile;
    $ec = new ExtraConfig();
    $custom .= $ec->getDescription();
}

$theme = $config->getTheme();
?>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="<?php echo $custom; ?>">
<link rel="icon" href="<?php echo $global['webSiteRootURL']; ?>view/img/favicon.png">
<!-- <link rel="stylesheet" type="text/css" media="only screen and (max-device-width: 768px)" href="view/css/mobile.css" /> -->
<link href="<?php echo $global['webSiteRootURL']; ?>view/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo $global['webSiteRootURL']; ?>view/js/webui-popover/jquery.webui-popover.min.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo $global['webSiteRootURL']; ?>view/css/fontawesome-free-5.5.0-web/css/all.min.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo $global['webSiteRootURL']; ?>view/css/flagstrap/css/flags.css" rel="stylesheet" type="text/css"/>
<?php
$cssFiles = array();
$cssFiles[] = "view/js/seetalert/sweetalert.css";
$cssFiles[] = "view/bootstrap/bootstrapSelectPicker/css/bootstrap-select.min.css";
$cssFiles[] = "view/js/bootgrid/jquery.bootgrid.css";
$cssFiles[] = "view/css/custom/{$theme}.css";
$cssFiles[] = "view/css/main.css";
//$cssFiles[] = "view/js/bootstrap-toggle/bootstrap-toggle.min.css";
$cssFiles = array_merge($cssFiles, YouPHPTubePlugin::getCSSFiles());
$cssURL = combineFiles($cssFiles, "css");
?>
<link href="<?php echo $cssURL; ?>" rel="stylesheet" type="text/css"/>
<?php
if (isRTL()) {
    ?>
    <style>
        .principalContainer, #mainContainer, #bigVideo, .mainArea, .galleryVideo, #sidebar, .navbar-header li{
            direction:rtl;
            unicode-bidi:embed;
        }
        #sidebar .nav{
            padding-right: 0;
        }
        .dropdown-menu, .navbar-header li a, #sideBarContainer .btn {
            text-align: right !important;
        }
        .dropdown-submenu a{
            width: 100%;
        }
        .galeryDetails div{
            float: right !important;
        }
        #saveCommentBtn{
            border-width: 1px;
            border-right-width: 0;
        }
    </style>    
    <?php
}
?>
<script src="<?php echo $global['webSiteRootURL']; ?>view/js/jquery-3.3.1.min.js"></script>
<script>
    var webSiteRootURL = '<?php echo $global['webSiteRootURL']; ?>';
    var player;
</script>
<?php
if (!$config->getDisable_analytics()) {
    ?>
    <script>
        // YouPHPTube Analytics
        (function (i, s, o, g, r, a, m) {
            i['GoogleAnalyticsObject'] = r;
            i[r] = i[r] || function () {
                (i[r].q = i[r].q || []).push(arguments)
            }, i[r].l = 1 * new Date();
            a = s.createElement(o),
                    m = s.getElementsByTagName(o)[0];
            a.async = 1;
            a.src = g;
            m.parentNode.insertBefore(a, m)
        })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');

        ga('create', 'UA-96597943-1', 'auto', 'youPHPTube');
        ga('youPHPTube.send', 'pageview');
    </script>
    <?php
}
echo $config->getHead();
echo $head;
if (!empty($video['users_id'])) {
    if (!empty($video)) {
        $userAnalytics = new User($video['users_id']);
        echo $userAnalytics->getAnalytics();
        unset($userAnalytics);
    }
}
?>
