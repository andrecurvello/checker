<?php

$json_version = array();

include_once("config_probe.php");

function check_piwigo_local(){
    $file = PIWIGO."/include/constants.php";
    
    if(!file_exists($file)) return "0";
    
    $handle = fopen($file, "r");
    if($handle) {
        $contents = '';
        while (!feof($handle)) { $contents .= fread($handle, 8192);}
        fclose($handle);
        
        if(preg_match("/define\('PHPWG_VERSION', '(.*)'\);/", $contents, $matches))
            return $matches[1];
        else
            return "0";
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
        
        if(preg_match("/getVersionString\(\) {\n(.*)return '(.*)';/", $contents, $matches))
            return $matches[2];
        else
            return "0";
    }
    
    return "0";
}

function check_owncloud_remote(){
    $ocs_latest = file_get_contents("https://raw.github.com/owncloud/core/stable45/lib/util.php");
    if($ocs_latest) {
        if(preg_match("/getVersionString\(\) {\n(.*)return '(.*)';/", $ocs_latest, $matches))
            return $matches[2];
        else
            return "0";
    }
    return "0";
}
    
function check_phpsysinfo_local(){
    
    $file = PHPSYSINFO."/includes/class.CommonFunctions.inc.php";
    
    if(file_exists($file)) {
        $handle = fopen($file, "r");
        if($handle) {
            $contents = '';
            while (!feof($handle)) { $contents .= fread($handle, 8192); }
            fclose($handle);
            
            if(preg_match("/const PSI_VERSION = '(.*)'/", $contents, $matches))
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
            
            if(preg_match("/define\('PSI_VERSION','(.*)'\);/", $contents, $matches))
                return $matches[1];
            else 
                return "0";
        }
    }
    
    return "0";
}

function check_phpsysinfo_remote(){
    $psi_file = file_get_contents("https://raw.github.com/rk4an/phpsysinfo/stable/config.php");
    if($psi_file) {
        if(preg_match("/define\('PSI_VERSION','(.*)'\);/", $psi_file, $matches))
            return $matches[1];
        else
            return "0";
    }
    return "0";
}


function check_mediawiki_local(){
    $file = MEDIAWIKI."/includes/DefaultSettings.php";
    
    if(!file_exists($file)) return "0";
    
    $handle = fopen($file, "r");
    if($handle) {
        $contents = '';
        while (!feof($handle)) { $contents .= fread($handle, 8192); }
        fclose($handle);
        
        if(preg_match("/wgVersion = '(.*)'/", $contents, $matches))
            return $matches[1];
        else
            return "0";
    }
    return "0";
}

function check_mediawiki_remote(){
    /**
     * The default ls in OS X does not have version sort capabilities 
     *
     * exec("git ls-remote --tags https://gerrit.wikimedia.org/r/p/mediawiki/core.git | cut  -f2 | tr -d 'refs/tags/' | sort -r --version-sort --field-separator=. -k2 | head -n 1", $output);
     */
    exec("git ls-remote --tags https://gerrit.wikimedia.org/r/p/mediawiki/core.git | cut  -f2 | tr -d 'refs/tags/' | sort -t. -k 1,1nr -k 2,2nr -k 3,3nr -k 4,4nr | head -n 1", $output);
    return $output[0];
}

function check_dokuwiki_local(){
    
    $file = DOKUWIKI."/VERSION";
    
    if(!file_exists($file)) return "0";
    
    $handle = fopen($file, "r");
    if($handle) {
        $contents = '';
        while (!feof($handle)) { $contents .= fread($handle, 8192);}
        fclose($handle);
        
        return $contents;
    }
    return "0";
}

function check_dokuwiki_remote(){
    $dokuwiki_file = file_get_contents("https://raw.github.com/splitbrain/dokuwiki/stable/VERSION");
    if($dokuwiki_file) {
        return $dokuwiki_file;
    }
    return "0";
}

function check_phpmyadmin_local(){
    
    $file = PHPMYADMIN."/libraries/Config.class.php";
    
    if(!file_exists($file)) return "0";
    
    $handle = fopen( $file, "r");
    if($handle) {
        $contents = '';
        while (!feof($handle)) { $contents .= fread($handle, 8192);}
        fclose($handle);
        
        if(preg_match("/this->set\('PMA_VERSION', '(.*)'\);/", $contents, $matches))
            return $matches[1];
        else
            return "0";
    }
    return "0";
}

function check_phpmyadmin_remote(){
    $pma_latest = file_get_contents("http://www.phpmyadmin.net/home_page/version.js");
    if($pma_latest) {
        preg_match("/PMA_latest_version = '(.*)'/", $pma_latest, $matches);
        return $matches[1];
    }
    return "0";
}

function check_checker_local(){
    $file = "VERSION";
    
    if(!file_exists($file)) return "0";
    
    $handle = fopen($file , "r");
    if($handle) {
        $contents = '';
        while (!feof($handle)) { $contents .= fread($handle, 8192);}
        fclose($handle);
        
        return $contents;
    }
    return "0";
}

function check_checker_remote(){
    $checker_file = file_get_contents("https://raw.github.com/rk4an/checker/dev/probe/VERSION");
    if($checker_file) {
        return $checker_file;
    }
    return "0";
}

function check_dotclear_local(){
    $file = DOTCLEAR."/inc/prepend.php";
    
    if(!file_exists($file)) return "0";
    
    $handle = fopen($file, "r");
    if($handle) {
        $contents = '';
        while (!feof($handle)) { $contents .= fread($handle, 8192);}
        fclose($handle);

        if(preg_match("/define\('DC_VERSION','(.*)'\);/", $contents, $matches))
            return $matches[1];
        else
            return "0";
    }
    return "0";
}

function check_dotclear_remote(){
    $contents = file_get_contents("http://download.dotclear.org/versions.xml");
    
    if($contents) {
        if(preg_match("/name=\"stable\" version=\"(.*)\"/", $contents, $matches))
            return $matches[1];
        else
            return "0";
    }
    return "0";
}


function check_gitlab_local(){
    $file = GITLAB."/VERSION";
    
    if(!file_exists($file)) return "0";
    
    $handle = fopen($file, "r");
    if($handle) {
        $contents = '';
        while (!feof($handle)) { $contents .= fread($handle, 8192);}
        fclose($handle);
        
        return $contents;
    }
    return "0";
}

function check_gitlab_remote(){
    $contents = file_get_contents("https://raw.github.com/gitlabhq/gitlabhq/stable/VERSION");
    if($contents) {
        return $contents;
    }
    return "0";
}


function check_symfony_local(){
    $file = SYMFONY."/composer.lock";
    
    if(!file_exists($file)) return "0";
    
    $handle = fopen($file, "r");
    if($handle) {
        $contents = '';
        while (!feof($handle)) { $contents .= fread($handle, 8192);}
        fclose($handle);
        if(preg_match('/"name": "symfony\/symfony",'."\n".'(.*)"version": "v(.*)",/', $contents, $matches))
            return $matches[2];
        else
            return "0";
    }
    return "0";
}

function check_symfony_remote(){
    $result = file_get_contents("https://raw.github.com/symfony/symfony-standard/2.1/composer.lock");
    if($result) {
        if(preg_match('/"name": "symfony\/symfony",'."\n".'(.*)"version": "v(.*)",/', $result, $matches))
            return $matches[2];
        else
            return "0";
    }
    return "0";
}

function check_wordpress_local(){
    $file = WORDPRESS."/wp-includes/version.php";
    
    if(!file_exists($file)) return "0";
    
    $handle = fopen($file, "r");
    if($handle) {
        $contents = '';
        while (!feof($handle)) { $contents .= fread($handle, 8192);}
        fclose($handle);
        
        if(preg_match("/wp_version = '(.*)';/", $contents, $matches))
            return $matches[1];
        else
            return "0";
    }
    return "0";
}

function check_wordpress_remote(){
    $wordpress_latest = file_get_contents("http://api.wordpress.org/core/version-check/1.6/");
    if($wordpress_latest) {
        $wordpress_array = unserialize($wordpress_latest);
        return $wordpress_array['offers'][0]['current'];
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
