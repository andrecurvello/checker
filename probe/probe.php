<?php

require_once("functions.php");
require_once("config_probe.php");

$json_version = array();

if(defined("PIWIGO")) {
    $json_version['Piwigo']['local'] = check_piwigo_local();
    $json_version['Piwigo']['remote'] = check_piwigo_remote();
}

if(defined("OWNCLOUD")) {
    $json_version['OwnCloud']['local'] = check_owncloud_local();
    $json_version['OwnCloud']['remote'] = check_owncloud_remote();
}

if(defined("PHPSYSINFO")) {
    $json_version['phpSysInfo']['local'] = check_phpsysinfo_local();
    $json_version['phpSysInfo']['remote'] = check_phpsysinfo_remote();
}

if(defined("MEDIAWIKI")) {
    $json_version['MediaWiki']['local'] = check_mediawiki_local();
    $json_version['MediaWiki']['remote'] = check_mediawiki_remote();
}

if(defined("DOKUWIKI")) {
    $json_version['Dokuwiki']['local'] = check_dokuwiki_local();
    $json_version['Dokuwiki']['remote'] = check_dokuwiki_remote();
}

if(defined("PHPMYADMIN")) {
    $json_version['phpMyAdmin']['local'] = check_phpmyadmin_local();
    $json_version['phpMyAdmin']['remote'] = check_phpmyadmin_remote();
}

if(defined("WORDPRESS")) {
    $json_version['Wordpress']['local'] = check_wordpress_local();
    $json_version['Wordpress']['remote'] = check_wordpress_remote();
}

if(defined("DOTCLEAR")) {
    $json_version['Dotclear']['local'] = check_dotclear_local();
    $json_version['Dotclear']['remote'] = check_dotclear_remote();
}

if(defined("GITLAB")) {
    $json_version['Gitlab']['local'] = check_gitlab_local();
    $json_version['Gitlab']['remote'] = check_gitlab_remote();
}

if(defined("SYMFONY")) {
    $json_version['Symfony']['local'] = check_symfony_local();
    $json_version['Symfony']['remote'] = check_symfony_remote();
}

if(defined("PLUXML")) {
    $json_version['PluXml']['local'] = check_pluxml_local();
    $json_version['PluXml']['remote'] = check_pluxml_remote();
}

$json_version['Checker']['local'] = check_checker_local();
$json_version['Checker']['remote'] = check_checker_remote();

echo json_encode(array($json_version));

?>
