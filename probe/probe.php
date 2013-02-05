<?php

$json_version = array();

require_once("functions.php");
require_once("config_probe.php");

if(defined("PIWIGO")) {
    $piwigo['Piwigo']['local'] = check_piwigo_local();
    $piwigo['Piwigo']['remote'] = check_piwigo_remote();
    $json_version[] = $piwigo;
}

if(defined("OWNCLOUD")) {
    $ocs['OwnCloud']['local'] = check_owncloud_local();
    $ocs['OwnCloud']['remote'] = check_owncloud_remote();
    $json_version[] = $ocs;
}

if(defined("PHPSYSINFO")) {
    $phpsysinfo['phpSysInfo']['local'] = check_phpsysinfo_local();
    $phpsysinfo['phpSysInfo']['remote'] = check_phpsysinfo_remote();
    $json_version[] = $phpsysinfo;
}

if(defined("MEDIAWIKI")) {
    $mediawiki['MediaWiki']['local'] = check_mediawiki_local();
    $mediawiki['MediaWiki']['remote'] = check_mediawiki_remote();
    $json_version[] = $mediawiki;
}

if(defined("DOKUWIKI")) {
    $dokuwiki['Dokuwiki']['local'] = check_dokuwiki_local();
    $dokuwiki['Dokuwiki']['remote'] = check_dokuwiki_remote();
    
    $json_version[] = $dokuwiki;
}

if(defined("PHPMYADMIN")) {
    $pma['phpMyAdmin']['local'] = check_phpmyadmin_local();
    $pma['phpMyAdmin']['remote'] = check_phpmyadmin_remote();
    $json_version[] = $pma;
}

if(defined("WORDPRESS")) {
    $wordpress['Wordpress']['local'] = check_wordpress_local();
    $wordpress['Wordpress']['remote'] = check_wordpress_remote();
    $json_version[] = $wordpress;
}

if(defined("DOTCLEAR")) {
    $dotclear['Dotclear']['local'] = check_dotclear_local();
    $dotclear['Dotclear']['remote'] = check_dotclear_remote();
    $json_version[] = $dotclear;
}

if(defined("GITLAB")) {
    $gitlab['Gitlab']['local'] = check_gitlab_local();
    $gitlab['Gitlab']['remote'] = check_gitlab_remote();
    $json_version[] = $gitlab;
}

if(defined("SYMFONY")) {
    $sf['Symfony']['local'] = check_symfony_local();
    $sf['Symfony']['remote'] = check_symfony_remote();
    $json_version[] = $sf;
}

$checker['Checker']['local'] = check_checker_local();
$checker['Checker']['remote'] = check_checker_remote();
$json_version[] = $checker;

echo json_encode($json_version);

?>
