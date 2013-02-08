<?php

//error_reporting(0);
header("content-type: application/json");

$nodes = array(
    "http://piwigo.org/download/all_versions.php",
    "https://raw.github.com/owncloud/core/stable45/lib/util.php",
    "https://raw.github.com/rk4an/phpsysinfo/stable/config.php",
    "https://raw.github.com/splitbrain/dokuwiki/stable/VERSION",
    "http://www.phpmyadmin.net/home_page/version.js",
    "https://raw.github.com/rk4an/checker/dev/probe/VERSION",
    "http://download.dotclear.org/versions.xml",
    "https://raw.github.com/gitlabhq/gitlabhq/stable/VERSION",
    "https://raw.github.com/pluxml/PluXml/master/version",
    "http://api.wordpress.org/core/version-check/1.6/",
    "https://raw.github.com/symfony/symfony-standard/2.1/composer.lock",
);

$results = get_nodes($nodes);

function read_file($file){
    if(!file_exists($file)) 
        return false;

    return @file_get_contents($file);
}


function get_nodes($nodes){

    $node_count = count($nodes);

    $curl_arr = array();
    $master = curl_multi_init();

    for($i = 0; $i < $node_count; $i++) {
        $url =$nodes[$i];
        $curl_arr[$i] = curl_init($url);
        curl_setopt($curl_arr[$i], CURLOPT_RETURNTRANSFER, true);
        curl_multi_add_handle($master, $curl_arr[$i]);
    }

    do {
        curl_multi_exec($master,$running);
    } while($running > 0);


    for($i = 0; $i < $node_count; $i++) {
        $results[] = curl_multi_getcontent  ( $curl_arr[$i]  );
    }
    
    return $results;
}


function read_remote_file($i) {
    global $results;
    return $results[$i];
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
    $contents = read_remote_file(0);
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
    $contents = read_remote_file(1);
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
    $contents = read_remote_file(2);
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
    $contents = read_remote_file(3);
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
    $contents = read_remote_file(4);
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
    $contents = read_remote_file(5);
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
    $contents = read_remote_file(6);
    
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
    $contents = read_remote_file(7);
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
    $contents = read_remote_file(10);
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
    $contents = read_remote_file(9);
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
    $contents = read_remote_file(8);
    if($contents) {
        return trim($contents);
    }
    return "0";
}

?>
