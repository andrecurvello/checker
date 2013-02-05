<?php

$json_version = array();

require_once("config_probe.php");

function check_piwigo_local(){
    $file = PIWIGO."/include/constants.php";
    
    if(!file_exists($file)) return "0";
    
    $handle = fopen($file, "r");
    if($handle) {
        $contents = '';
        while (!feof($handle)) { $contents .= fread($handle, 8192);}
        fclose($handle);
        
        preg_match("/define\('PHPWG_VERSION', '(.*)'\);/", $contents, $matches);
        
        return $matches[1];
    }
    return "0";
}

function check_piwigo_remote(){
    $result = file_get_contents("http://piwigo.org/download/all_versions.php");
    if($result) {
        $all_piwigo_versions = @explode("\n", $result);
        $new_piwigo_version = trim($all_piwigo_versions[0]);
        return $new_piwigo_version;
    }
    return "0";
}

function check_owncloud_local(){
    
    $file = OWNCLOUD."/lib/util.php";
    
    if(!file_exists($file)) return "0";
    
    $handle = fopen($file, "r");
    if($handle) {
        $contents = '';
        while (!feof($handle)) { $contents .= fread($handle, 8192);}
        fclose($handle);
        
        preg_match("/getVersionString\(\) {\n(.*)return '(.*)';/", $contents, $matches);
        return $matches[2];
    }
    
    return "0";
}

function check_owncloud_remote(){
    $ocs_latest = file_get_contents("https://raw.github.com/owncloud/core/stable45/lib/util.php");
    if($ocs_latest) {
        preg_match("/getVersionString\(\) {\n(.*)return '(.*)';/", $ocs_latest, $matches);
        return $matches[2];
    }
}
    
function check_phpsysinfo_local(){
    
    $file = PHPSYSINFO."/includes/class.CommonFunctions.inc.php";
    
    if(file_exists($file)) {
        $handle = fopen($file, "r");
        if($handle) {
            $contents = '';
            while (!feof($handle)) { $contents .= fread($handle, 8192); }
            fclose($handle);
            
            preg_match("/const PSI_VERSION = '(.*)'/", $contents, $matches);
            return $matches[1];
        }
    }
    
    //try 3.1 version
    $file = PHPSYSINFO."/config.php";
    if(file_exists($file)) {
        $handle = fopen($file, "rb");
        if($handle) {
            $contents = '';
            while (!feof($handle)) { $contents .= fread($handle, 8192); }
            fclose($handle);
            
            preg_match("/define\('PSI_VERSION','(.*)'\);/", $contents, $matches);
            return $matches[1];
        }
    }
    
    return "0";
}

function check_phpsysinfo_remote(){
    $psi_file = file_get_contents("https://raw.github.com/rk4an/phpsysinfo/stable/config.php");
    if($psi_file) {
        preg_match("/define\('PSI_VERSION','(.*)'\);/", $psi_file, $matches);
        return $matches[1];
    }
    return "0";
}

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

    /**
     * The default ls in OS X does not have version sort capabilities 
     *
     * exec("git ls-remote --tags https://gerrit.wikimedia.org/r/p/mediawiki/core.git | cut  -f2 | tr -d 'refs/tags/' | sort -r --version-sort --field-separator=. -k2 | head -n 1", $output);
     */
    exec("git ls-remote --tags https://gerrit.wikimedia.org/r/p/mediawiki/core.git | cut  -f2 | tr -d 'refs/tags/' | sort -t. -k 1,1nr -k 2,2nr -k 3,3nr -k 4,4nr | head -n 1", $output);
    $mediawiki['MediaWiki']['remote'] = $output[0];

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

    $handle = fopen(SYMFONY."/composer.lock", "r");
    if($handle) {
        $contents = '';
        while (!feof($handle)) { $contents .= fread($handle, 8192);}
        fclose($handle);
        preg_match('/"name": "symfony\/symfony",'."\n".'(.*)"version": "v(.*)",/', $contents, $matches);
        $sf['Symfony']['local'] = $matches[2];
    }

    $result = file_get_contents("https://raw.github.com/symfony/symfony-standard/2.1/composer.lock");
    if($result) {
        preg_match('/"name": "symfony\/symfony",'."\n".'(.*)"version": "v(.*)",/', $result, $matches);
        $sf['Symfony']['remote'] = $matches[2];
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

$checker_file = file_get_contents("https://raw.github.com/rk4an/checker/master/probe/VERSION");
if($checker_file) {
    $checker['Checker']['remote'] = $checker_file;
}
$json_version[] = $checker;

echo json_encode($json_version);

?>
