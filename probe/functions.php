<?php

function read_file($file){
    if(!file_exists($file)) 
        return false;
    
    $handle = fopen($file, "r");
    if($handle) {
        $contents = '';
        while (!feof($handle)) { 
            $contents .= fread($handle, 8192);
        }
        fclose($handle);
        return $contents;
    }
    return false;
}

function check_piwigo_local(){
    $contents = read_file(PIWIGO."/include/constants.php");
    if(!$contents) return "0";
    
    if(preg_match("/define\('PHPWG_VERSION', '(.*)'\);/", $contents, $matches))
        return $matches[1];
    else
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
    $contents = read_file(OWNCLOUD."/lib/util.php");
    if(!$contents) return "0";
        
    if(preg_match("/getVersionString\(\) {\n(.*)return '(.*)';/", $contents, $matches))
        return $matches[2];
    else
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
    $contents = read_file(MEDIAWIKI."/includes/DefaultSettings.php");
    if(!$contents) return "0";

    if(preg_match("/wgVersion = '(.*)'/", $contents, $matches))
        return $matches[1];
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
    return $contents;
}

function check_dokuwiki_remote(){
    $dokuwiki_file = file_get_contents("https://raw.github.com/splitbrain/dokuwiki/stable/VERSION");
    if($dokuwiki_file) {
        return $dokuwiki_file;
    }
    return "0";
}

function check_phpmyadmin_local(){
    $contents = read_file(PHPMYADMIN."/libraries/Config.class.php");
    if(!$contents) return "0";

    if(preg_match("/this->set\('PMA_VERSION', '(.*)'\);/", $contents, $matches))
        return $matches[1];
    else
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
    $contents = read_file("VERSION");
    if(!$contents) return "0";
    return $contents;
}

function check_checker_remote(){
    $checker_file = file_get_contents("https://raw.github.com/rk4an/checker/dev/probe/VERSION");
    if($checker_file) {
        return $checker_file;
    }
    return "0";
}

function check_dotclear_local(){
    $contents = read_file(DOTCLEAR."/inc/prepend.php");
    if(!$contents) return "0";
   
    if(preg_match("/define\('DC_VERSION','(.*)'\);/", $contents, $matches))
        return $matches[1];
    else
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
    $contents = read_file(GITLAB."/VERSION");
    if(!$contents) return "0";
    return $contents;
}

function check_gitlab_remote(){
    $contents = file_get_contents("https://raw.github.com/gitlabhq/gitlabhq/stable/VERSION");
    if($contents) {
        return $contents;
    }
    return "0";
}


function check_symfony_local(){
    $contents = read_file(SYMFONY."/composer.lock");
    if(!$contents) return "0";
    
    if(preg_match('/"name": "symfony\/symfony",'."\n".'(.*)"version": "v(.*)",/', $contents, $matches))
        return $matches[2];
    else
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
    $contents = read_file(WORDPRESS."/wp-includes/version.php");
    if(!$contents) return "0";
    
    if(preg_match("/wp_version = '(.*)';/", $contents, $matches))
        return $matches[1];
    else
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

?>
