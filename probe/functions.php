<?php

error_reporting(0);
header("content-type: application/json");

function read_file($file){
    if(!file_exists($file)) 
        return false;

    return @file_get_contents($file);
}

function read_remote_file($url) {
    $opts = array(
       'http'=> array(
         'header' => 'Connection: close'
     )
    );
    $context = stream_context_create($opts);
    return @file_get_contents($url, false, $context);
    
    /*$ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    return curl_exec($ch);*/
}

function check_piwigo_local(){
    $contents = read_file(PIWIGO."/include/constants.php");
    if(!$contents) return "0";
    
    if(preg_match("/define\('PHPWG_VERSION', '(.*)'\);/", $contents, $matches))
        return trim($matches[1]);
    else
        return "0";
}

function check_piwigo_remote(){
    $contents = read_remote_file("http://piwigo.org/download/all_versions.php");
    if($contents) {
        $all_piwigo_versions = @explode("\n", $contents);
        $new_piwigo_version = trim($all_piwigo_versions[0]);
        return trim($new_piwigo_version);
    }
    return "0";
}

function check_owncloud_local(){
    $contents = read_file(OWNCLOUD."/lib/util.php");
    if(!$contents) return "0";
        
    if(preg_match("/getVersionString\(\) {\n(.*)return '(.*)';/", $contents, $matches))
        return trim($matches[2]);
    else
        return "0";
}

function check_owncloud_remote(){
    $contents = read_remote_file("https://raw.github.com/owncloud/core/stable45/lib/util.php");
    if($contents) {
        if(preg_match("/getVersionString\(\) {\n(.*)return '(.*)';/", $contents, $matches))
            return trim($matches[2]);
        else
            return "0";
    }
    return "0";
}
    
function check_phpsysinfo_local(){
    
    $contents = read_file(PHPSYSINFO."/includes/class.CommonFunctions.inc.php");
    if(!$contents) return "0";
    
    if(preg_match("/const PSI_VERSION = '(.*)'/", $contents, $matches))
        return trim($matches[1]);
    else {
        $contents = read_file(PHPSYSINFO."/config.php");
        if(!$contents) return "0";
        
        if(preg_match("/define\('PSI_VERSION','(.*)'\);/", $contents, $matches))
            return trim($matches[1]);
        else 
            return "0";
    }
    
    return "0";
}

function check_phpsysinfo_remote(){
    $contents = read_remote_file("https://raw.github.com/rk4an/phpsysinfo/stable/config.php");
    if($contents) {
        if(preg_match("/define\('PSI_VERSION','(.*)'\);/", $contents, $matches))
            return trim($matches[1]);
        else
            return "0";
    }
    return "0";
}


function check_mediawiki_local(){
    $contents = read_file(MEDIAWIKI."/includes/DefaultSettings.php");
    if(!$contents) return "0";

    if(preg_match("/wgVersion = '(.*)'/", $contents, $matches))
        return trim($matches[1]);
    else
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
    $contents = read_file(DOKUWIKI."/VERSION");
    if(!$contents) return "0";
    return trim($contents);
}

function check_dokuwiki_remote(){
    $contents = read_remote_file("https://raw.github.com/splitbrain/dokuwiki/stable/VERSION");
    if($contents) {
        return trim($contents);
    }
    return "0";
}

function check_phpmyadmin_local(){
    $contents = read_file(PHPMYADMIN."/libraries/Config.class.php");
    if(!$contents) return "0";

    if(preg_match("/this->set\('PMA_VERSION', '(.*)'\);/", $contents, $matches))
        return trim($matches[1]);
    else
        return "0";
}

function check_phpmyadmin_remote(){
    $contents = read_remote_file("http://www.phpmyadmin.net/home_page/version.js");
    if($contents) {
        preg_match("/PMA_latest_version = '(.*)'/", $contents, $matches);
        return trim($matches[1]);
    }
    return "0";
}

function check_checker_local(){
    $contents = read_file("VERSION");
    if(!$contents) return "0";
    return trim($contents);
}

function check_checker_remote(){
    $contents = read_remote_file("https://raw.github.com/rk4an/checker/dev/probe/VERSION");
    if($contents) {
        return trim($contents);
    }
    return "0";
}

function check_dotclear_local(){
    $contents = read_file(DOTCLEAR."/inc/prepend.php");
    if(!$contents) return "0";
   
    if(preg_match("/define\('DC_VERSION','(.*)'\);/", $contents, $matches))
        return trim($matches[1]);
    else
        return "0";
}

function check_dotclear_remote(){
    $contents = read_remote_file("http://download.dotclear.org/versions.xml");
    
    if($contents) {
        if(preg_match("/name=\"stable\" version=\"(.*)\"/", $contents, $matches))
            return trim($matches[1]);
        else
            return "0";
    }
    return "0";
}


function check_gitlab_local(){
    $contents = read_file(GITLAB."/VERSION");
    if(!$contents) return "0";
    return trim($contents);
}

function check_gitlab_remote(){
    $contents = read_remote_file("https://raw.github.com/gitlabhq/gitlabhq/stable/VERSION");
    if($contents) {
        return trim($contents);
    }
    return "0";
}


function check_symfony_local(){
    $contents = read_file(SYMFONY."/composer.lock");
    if(!$contents) return "0";
    
    if(preg_match('/"name": "symfony\/symfony",'."\n".'(.*)"version": "v(.*)",/', $contents, $matches))
        return trim($matches[2]);
    else
        return "0";
}

function check_symfony_remote(){
    $contents = read_remote_file("https://raw.github.com/symfony/symfony-standard/2.1/composer.lock");
    if($contents) {
        if(preg_match('/"name": "symfony\/symfony",'."\n".'(.*)"version": "v(.*)",/', $contents, $matches))
            return trim($matches[2]);
        else
            return "0";
    }
    return "0";
}

function check_wordpress_local(){
    $contents = read_file(WORDPRESS."/wp-includes/version.php");
    if(!$contents) return "0";
    
    if(preg_match("/wp_version = '(.*)';/", $contents, $matches))
        return trim($matches[1]);
    else
        return "0";
}

function check_wordpress_remote(){
    $contents = read_remote_file("http://api.wordpress.org/core/version-check/1.6/");
    if($contents) {
        $wordpress_array = unserialize($contents);
        return trim($wordpress_array['offers'][0]['current']);
    }
    return "0";
}

function check_pluxml_local(){
    $contents = read_file(PLUXML."/version");
    if(!$contents) return "0";
    return trim($contents);
}

function check_pluxml_remote(){
    $contents = read_remote_file("https://raw.github.com/pluxml/PluXml/master/version");
    if($contents) {
        return trim($contents);
    }
    return "0";
}

?>
