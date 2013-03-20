<?php

$remote_url = array(
    "piwigo" => "http://piwigo.org/download/all_versions.php",
    "owncloud" => "https://raw.github.com/owncloud/core/stable5/lib/util.php",
    "phpsysinfo" => "https://raw.github.com/rk4an/phpsysinfo/stable/config.php",
    "dokuwiki" => "https://raw.github.com/splitbrain/dokuwiki/stable/VERSION",
    "phpmyadmin" => "http://www.phpmyadmin.net/home_page/version.js",
    "checker" => "https://raw.github.com/rk4an/checker/dev/probe/VERSION",
    "dotclear" => "http://download.dotclear.org/versions.xml",
    "gitlab" => "https://raw.github.com/gitlabhq/gitlabhq/stable/VERSION",
    "pluxml" => "https://raw.github.com/pluxml/PluXml/master/version",
    "wordpress" => "http://api.wordpress.org/core/version-check/1.6/",
    "symfony" => "https://packagist.org/p/symfony/symfony.json",
    "mediawiki" => "https://packagist.org/p/mediawiki/core.json"
);

function get_remote_url() {
    global $remote_url;
    return $remote_url;
}

?>
