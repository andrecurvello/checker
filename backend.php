<?php

$json_version = array();

require_once("config.php");

if(defined("PIWIGO")) {
    $piwigo['Piwigo']['local'] = "0";
    $piwigo['Piwigo']['remote'] = "0";
    
    $handle = fopen(PIWIGO."/include/constants.php", "r");
    if($handle) {
        $contents = '';
        while (!feof($handle)) { $contents .= fread($handle, 8192);}
        fclose($handle);
        
        preg_match("/define\('PHPWG_VERSION', '(.*)'\);/", $contents, $matches);
        $piwigo['Piwigo']['local'] = $matches[1];
    }

    $result = file_get_contents("http://piwigo.org/download/all_versions.php");
    if($result) {
        $all_piwigo_versions = @explode("\n", $result);
        $new_piwigo_version = trim($all_piwigo_versions[0]);
        $piwigo['Piwigo']['remote'] = $new_piwigo_version;
    }

    $json_version[] = $piwigo;
}

if(defined("OWNCLOUD")) {
    $ocs['OwnCloud']['local'] = "0";
    $ocs['OwnCloud']['remote'] = "0";
    
    $handle = fopen(OWNCLOUD."/lib/util.php", "r");
    if($handle) {
        $contents = '';
        while (!feof($handle)) { $contents .= fread($handle, 8192);}
        fclose($handle);
        
        preg_match("/getVersionString\(\) {\n    	return '(.*)';/", $contents, $matches);
        $ocs['OwnCloud']['local'] = $matches[1];
    }
    
    $ocs_latest = file_get_contents("https://raw.github.com/owncloud/core/stable45/lib/util.php");
    if($ocs_latest) {
        preg_match("/getVersionString\(\) {\n		return '(.*)';/", $ocs_latest, $matches);
        $ocs['OwnCloud']['remote'] = $matches[1];
    }
    
    /*include(OWNCLOUD."/lib/util.php");
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
    $ocs['OwnCloud']['remote'] = $tmp['versionstring'];*/
    
    

    $json_version[] = $ocs;
}

if(defined("PHPSYSINFO")) {
    $phpsysinfo['phpSysInfo']['local'] = "0";
    $phpsysinfo['phpSysInfo']['remote'] = "0";
    
    $handle = fopen(PHPSYSINFO."/includes/class.CommonFunctions.inc.php", "r");
    if($handle) {
        $contents = '';
        while (!feof($handle)) { $contents .= fread($handle, 8192); }
        fclose($handle);
        
        preg_match("/const PSI_VERSION = '(.*)'/", $contents, $matches);
        $phpsysinfo['phpSysInfo']['local'] = $matches[1];
    }

    //for 3.1.x version
    if($phpsysinfo['phpSysInfo']['local'] == 0) {
        $handle = fopen(PHPSYSINFO."/config.php", "rb");
        if($handle) {
            $contents = '';
            while (!feof($handle)) { $contents .= fread($handle, 8192); }
            fclose($handle);
            
            preg_match("/define\('PSI_VERSION','(.*)'\);/", $contents, $matches);
            $phpsysinfo['phpSysInfo']['local'] = $matches[1];
        }
    }

    //get latest tag of a local repo
    //(but you are not sure if dir is a git repo and the latest tag are stable tag, and we need to fetch before)
    //exec("cd ". $phpsysinfo_local . " && git describe", $output);
    //$phpsysinfo['phpSysInfo']['remote'] = $output;

    //get latest file for master branch on git
    $psi_file = file_get_contents("https://raw.github.com/rk4an/phpsysinfo/stable/config.php");
    if($psi_file) {
        preg_match("/define\('PSI_VERSION','(.*)'\);/", $psi_file, $matches);
        $phpsysinfo['phpSysInfo']['remote'] = $matches[1];
    }

    $json_version[] = $phpsysinfo;
}

if(defined("MEDIAWIKI")) {
    $mediawiki['MediaWiki']['local'] = "0";
    $mediawiki['MediaWiki']['remote'] = "0";
    
    $handle = fopen(MEDIAWIKI."/includes/DefaultSettings.php", "r");
    if($handle) {
        $contents = '';
        while (!feof($handle)) { $contents .= fread($handle, 8192); }
        fclose($handle);
        
        preg_match("/wgVersion = '(.*)'/", $contents, $matches);
        $mediawiki['MediaWiki']['local'] = $matches[1];
    }

    exec("git ls-remote --tags https://gerrit.wikimedia.org/r/p/mediawiki/core.git | cut  -f2 | tr -d 'refs/tags/' | sort -r --version-sort --field-separator=. -k2 | head -n 1", $output);
    $mediawiki['MediaWiki']['remote'] = $output;

    $json_version[] = $mediawiki;
}

if(defined("DOKUWIKI")) {
    $dokuwiki['Dokuwiki']['local'] = "0";
    $dokuwiki['Dokuwiki']['remote'] = "0";
    
    $handle = fopen(DOKUWIKI."/VERSION", "r");
    if($handle) {
        $contents = '';
        while (!feof($handle)) { $contents .= fread($handle, 8192);}
        fclose($handle);
        
        $dokuwiki['Dokuwiki']['local'] = $contents;
    }

    $dokuwiki_file = file_get_contents("https://raw.github.com/splitbrain/dokuwiki/stable/VERSION");
    if($dokuwiki_file) {
        $dokuwiki['Dokuwiki']['remote'] = $dokuwiki_file;
    }

    $json_version[] = $dokuwiki;
}

if(defined("PHPMYADMIN")) {
    $pma['phpMyAdmin']['local'] = "0";
    $pma['phpMyAdmin']['remote'] = "0";

    $handle = fopen(PHPMYADMIN."/libraries/Config.class.php", "r");
    if($handle) {
        $contents = '';
        while (!feof($handle)) { $contents .= fread($handle, 8192);}
        fclose($handle);
        
        preg_match("/this->set\('PMA_VERSION', '(.*)'\);/", $contents, $matches);
        $pma['phpMyAdmin']['local'] = $matches[1];
    }

    $pma_latest = file_get_contents("http://www.phpmyadmin.net/home_page/version.js");
    if($pma_latest) {
        preg_match("/PMA_latest_version = '(.*)'/", $pma_latest, $matches);
        $pma['phpMyAdmin']['remote'] = $matches[1];
    }

    $json_version[] = $pma;
}

if(defined("WORDPRESS")) {
    $wordpress['Wordpress']['local'] = "0";
    $wordpress['Wordpress']['remote'] = "0";
    
    $handle = fopen(WORDPRESS."/wp-includes/version.php", "r");
    if($handle) {
        $contents = '';
        while (!feof($handle)) { $contents .= fread($handle, 8192);}
        fclose($handle);
        
        preg_match("/wp_version = '(.*)';/", $contents, $matches);
        $wordpress['Wordpress']['local'] = $matches[1];
    }

    $wordpress_latest = file_get_contents("http://api.wordpress.org/core/version-check/1.6/");
    if($wordpress_latest) {
        $wordpress_array = unserialize($wordpress_latest);
        $wordpress['Wordpress']['remote'] = $wordpress_array['offers'][0]['current'];
    }

    $json_version[] = $wordpress;
}

if(defined("DOTCLEAR")) {
    $dotclear['Dotclear']['local'] = "0";
    $dotclear['Dotclear']['remote'] = "0";
    
    $handle = fopen(DOTCLEAR."/inc/prepend.php", "r");
    if($handle) {
        $contents = '';
        while (!feof($handle)) { $contents .= fread($handle, 8192);}
        fclose($handle);

        preg_match("/define\('DC_VERSION','(.*)'\);/", $contents, $matches);
        $dotclear['Dotclear']['local'] = $matches[1];
    }
    
    $contents = file_get_contents("http://download.dotclear.org/versions.xml");
    
    if($contents) {
        preg_match("/name=\"stable\" version=\"(.*)\"/", $contents, $matches);
        $dotclear['Dotclear']['remote'] = $matches[1];
    }

    $json_version[] = $dotclear;
}

if(defined("GITLAB")) {
    $gitlab['Gitlab']['local'] = "0";
    $gitlab['Gitlab']['remote'] = "0";
    
    $handle = fopen(GITLAB."/VERSION", "r");
    if($handle) {
        $contents = '';
        while (!feof($handle)) { $contents .= fread($handle, 8192);}
        fclose($handle);
        
        $gitlab['Gitlab']['local'] = $contents;
    }

    $contents = file_get_contents("https://raw.github.com/gitlabhq/gitlabhq/stable/VERSION");
    if($contents) {
        $gitlab['Gitlab']['remote'] = $contents;
    }

    $json_version[] = $gitlab;
}

if(defined("SYMFONY")) {
    $sf['Symfony']['local'] = "0";
    $sf['Symfony']['remote'] = "0";

    $handle = fopen(SYMFONY."/Component/HttpKernel/Kernel.php", "r");
    if($handle) {
        $contents = '';
        while (!feof($handle)) { $contents .= fread($handle, 8192);}
        fclose($handle);

        preg_match("/const VERSION         = '(.*)';/", $contents, $matches);
        $sf['Symfony']['local'] = $matches[1];
    }

    $result = file_get_contents("https://raw.github.com/symfony/symfony-standard/2.1/composer.lock");
    if($result) {
        preg_match("/https\:\/\/github.com\/symfony\/symfony\/archive\/v(.*).zip/", $result, $matches);
        $sf['Symfony']['remote'] = $matches[1];
    }

    $json_version[] = $sf;
}

$checker['Checker']['local'] = "0";
$checker['Checker']['remote'] = "0";
$handle = fopen("VERSION", "r");
if($handle) {
    $contents = '';
    while (!feof($handle)) { $contents .= fread($handle, 8192);}
    fclose($handle);
    
    $checker['Checker']['local'] = $contents;
}

$checker_file = file_get_contents("https://raw.github.com/rk4an/checker/master/VERSION");
if($checker_file) {
    $checker['Checker']['remote'] = $checker_file;
}
$json_version[] = $checker;

echo json_encode($json_version);

?>
