<?php

$json_version = array();

require_once("config.php");

if(defined("PIWIGO")) {
    $handle = fopen(PIWIGO."/include/constants.php", "rb");
    if($handle) {
        $contents = '';
        while (!feof($handle)) { $contents .= fread($handle, 8192);}
        fclose($handle);
    }
    preg_match("/define\('PHPWG_VERSION', '(.*)'\);/", $contents, $matches);
    $piwigo['Piwigo']['local'] = $matches[1];

    $result = file_get_contents("http://piwigo.org/download/all_versions.php");
    $all_piwigo_versions = @explode("\n", $result);
    $new_piwigo_version = trim($all_piwigo_versions[0]);
    $piwigo['Piwigo']['remote'] = $new_piwigo_version;

    $json_version[] = $piwigo;
}

if(defined("OWNCLOUD")) {
    include(OWNCLOUD."/lib/util.php");
    $ocs['OwnCloud']['local'] = OC_Util::getVersionString();
    
    
    $updaterurl='http://apps.owncloud.com/updater.php';
    $version = OC_Util::getVersion();
    $version['installed'] = '';
    $version['updated'] = '';
    $version['updatechannel'] = 'stable';
    $version['edition'] = "";
    $versionstring = implode('x',$version);
    $url = $updaterurl.'?version='.$versionstring;
    $ctx = stream_context_create(array( 'http' => array( 'timeout' => 10 )) ); 
    $xml = @file_get_contents($url, 0, $ctx);
    $data = @simplexml_load_string($xml);
    $tmp = array();
    $tmp['version'] = $data->version;
    $tmp['versionstring'] = $data->versionstring;
    $tmp['url'] = $data->url;
    $tmp['web'] = $data->web;
    if($tmp['versionstring'] == "") { $tmp['versionstring'] = OC_Util::getVersionString(); }
    $ocs['OwnCloud']['remote'] = $tmp['versionstring'];

    $json_version[] = $ocs;
}

if(defined("PHPSYSINFO")) {
    $handle = fopen(PHPSYSINFO."/includes/class.CommonFunctions.inc.php", "rb");
    if($handle) {
        $contents = '';
        while (!feof($handle)) { $contents .= fread($handle, 8192); }
        fclose($handle);
    }
    preg_match("/const PSI_VERSION = '(.*)'/", $contents, $matches);
    $phpsysinfo['phpSysInfo']['local'] = $matches[1];

    //get latest tag of a local repo
    //(but you are not sure if dir is a git repo and the latest tag are stable tag, and we need to fetch before)
    //exec("cd ". $phpsysinfo_local . " && git describe", $output);
    //$phpsysinfo['phpSysInfo']['remote'] = $output;

    //get latest file for master branch on git
    $psi_file = file_get_contents("https://raw.github.com/rk4an/phpsysinfo/master/includes/class.CommonFunctions.inc.php");
    preg_match("/const PSI_VERSION = '(.*)'/", $psi_file, $matches);
    $phpsysinfo['phpSysInfo']['remote'] = $matches[1];

    $json_version[] = $phpsysinfo;
}

if(defined("MEDIAWIKI")) {
    $handle = fopen(MEDIAWIKI."/includes/DefaultSettings.php", "rb");
    if($handle) {
        $contents = '';
        while (!feof($handle)) { $contents .= fread($handle, 8192); }
        fclose($handle);
    }
    preg_match("/wgVersion = '(.*)'/", $contents, $matches);
    $mediawiki['MediaWiki']['local'] = $matches[1];

    exec("git ls-remote --tags https://gerrit.wikimedia.org/r/p/mediawiki/core.git | cut  -f2 | tr -d 'refs/tags/' | sort -r --version-sort --field-separator=. -k2 | head -n 1", $output);
    $mediawiki['MediaWiki']['remote'] = $output;

    $json_version[] = $mediawiki;
}



if(defined("DOKUWIKI")) {
    $handle = fopen(DOKUWIKI."/VERSION", "rb");
    if($handle) {
        $contents = '';
        while (!feof($handle)) { $contents .= fread($handle, 8192);}
        fclose($handle);
    }
    $dokuwiki['Dokuwiki']['local'] = $contents;

    $dokuwiki_file = file_get_contents("https://raw.github.com/splitbrain/dokuwiki/stable/VERSION");
    $dokuwiki['Dokuwiki']['remote'] = $dokuwiki_file;

    $json_version[] = $dokuwiki;
}


if(defined("PHPMYADMIN")) {
    $handle = fopen(PHPMYADMIN."/libraries/Config.class.php", "rb");
    if($handle) {
        $contents = '';
        while (!feof($handle)) { $contents .= fread($handle, 8192);}
        fclose($handle);
    }
    preg_match("/this->set\('PMA_VERSION', '(.*)'\);/", $contents, $matches);
    $pma['phpMyAdmin']['local'] = $matches[1];

    $pma_latest = file_get_contents("http://www.phpmyadmin.net/home_page/version.js");
    preg_match("/PMA_latest_version = '(.*)'/", $pma_latest, $matches);
    $pma['phpMyAdmin']['remote'] = $matches[1];

    $json_version[] = $pma;
}

if(defined("WORDPRESS")) {
    $handle = fopen(WORDPRESS."/wp-includes/version.php", "rb");
    if($handle) {
        $contents = '';
        while (!feof($handle)) { $contents .= fread($handle, 8192);}
        fclose($handle);
    }
    preg_match("/wp_version = '(.*)';/", $contents, $matches);
    $wordpress['Wordpress']['local'] = $matches[1];

    $wordpress_latest = file_get_contents("http://api.wordpress.org/core/version-check/1.6/");
    $wordpress_array = unserialize($wordpress_latest);
    $wordpress['Wordpress']['remote'] = $wordpress_array['offers'][0]['current'];

    $json_version[] = $wordpress;
}

if(defined("DOTCLEAR")) {
    $handle = fopen(DOTCLEAR."/inc/prepend.php", "rb");
    if($handle) {
        $contents = '';
        while (!feof($handle)) { $contents .= fread($handle, 8192);}
        fclose($handle);
    }
    preg_match("/define\('DC_VERSION','(.*)'\);/", $contents, $matches);
    $dotclear['Dotclear']['local'] = $matches[1];

    
    $contents = file_get_contents("http://download.dotclear.org/versions.xml");
    //TODO: parse xml
    preg_match("/name=\"stable\" version=\"(.*)\"/", $contents, $matches);
    $dotclear['Dotclear']['remote'] = $matches[1];

    $json_version[] = $dotclear;
}

$handle = fopen("VERSION", "rb");
if($handle) {
    $contents = '';
    while (!feof($handle)) { $contents .= fread($handle, 8192);}
    fclose($handle);
}

$checker['Checker']['local'] = $contents;

$checker_file = file_get_contents("https://raw.github.com/rk4an/checker/master/VERSION");
$checker['Checker']['remote'] = $checker_file;

$json_version[] = $checker;
    

//Results
echo json_encode($json_version);

?>
