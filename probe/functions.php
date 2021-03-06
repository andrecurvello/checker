<?php

error_reporting(0);
header("content-type: application/json");

include 'VersionParser.php';

function read_file($file)
{
    if(!file_exists($file))

        return false;

    return @file_get_contents($file);
}

function get_nodes($nodes)
{
    $node_count = count($nodes);

    $curl_arr = array();
    $master = curl_multi_init();

    for ($i = 0; $i < $node_count; $i++) {
        $url =$nodes[$i];
        $curl_arr[$i] = curl_init($url);
        curl_setopt($curl_arr[$i], CURLOPT_RETURNTRANSFER, true);
        curl_multi_add_handle($master, $curl_arr[$i]);
    }

    do {
        curl_multi_exec($master,$running);
    } while ($running > 0);

    for ($i = 0; $i < $node_count; $i++) {
        $results[] = curl_multi_getcontent  ( $curl_arr[$i]  );
    }

    return $results;
}

function check_piwigo_local($url)
{
    $contents = read_file($url."/include/constants.php");
    if(!$contents) return "0";

    if(preg_match("/define\('PHPWG_VERSION', '(.*)'\);/", $contents, $matches))

        return trim($matches[1]);
    else
        return "0";
}

function check_piwigo_remote($contents)
{
    if ($contents) {
        $all_piwigo_versions = @explode("\n", $contents);
        $new_piwigo_version = trim($all_piwigo_versions[0]);

        return trim($new_piwigo_version);
    }

    return "0";
}

function check_owncloud_local($url)
{
    $contents = read_file($url."/lib/util.php");
    if(!$contents) return "0";

    if (preg_match("/getVersionString\(\) {\n(.*)return '(.*)';/", $contents, $matches))

        return trim($matches[2]);
    else
        return "0";
}

function check_owncloud_remote($contents)
{
    if ($contents) {
        if (preg_match("/getVersionString\(\) {\n(.*)return '(.*)';/", $contents, $matches))

            return trim($matches[2]);
        else
            return "0";
    }

    return "0";
}

function check_phpsysinfo_local($url)
{
    $contents = read_file($url."/includes/class.CommonFunctions.inc.php");
    if(!$contents) return "0";

    if(preg_match("/const PSI_VERSION = '(.*)'/", $contents, $matches))

        return trim($matches[1]);
    else {
        $contents = read_file($url."/config.php");
        if(!$contents) return "0";

        if(preg_match("/define\('PSI_VERSION','(.*)'\);/", $contents, $matches))

            return trim($matches[1]);
        else
            return "0";
    }

    return "0";
}

function check_phpsysinfo_remote($contents)
{
    if ($contents) {
        if(preg_match("/define\('PSI_VERSION','(.*)'\);/", $contents, $matches))

            return trim($matches[1]);
        else
            return "0";
    }

    return "0";
}

function check_mediawiki_local($url)
{
    $contents = read_file($url."/includes/DefaultSettings.php");
    if(!$contents) return "0";

    if(preg_match("/wgVersion = '(.*)'/", $contents, $matches))

        return trim($matches[1]);
    else
        return "0";
}

function check_mediawiki_remote($contents)
{
    if(!$contents) return "0";

    $json = json_decode($contents);
    $package = $json->{'packages'};

    return get_last_version_packagist($package);
}

function check_dokuwiki_local($url)
{
    $contents = read_file($url."/VERSION");
    if(!$contents) return "0";

    return trim($contents);
}

function check_dokuwiki_remote($contents)
{
    if ($contents) {
        return trim($contents);
    }

    return "0";
}

function check_phpmyadmin_local($url)
{
    $contents = read_file($url."/libraries/Config.class.php");
    if(!$contents) return "0";

    if(preg_match("/this->set\('PMA_VERSION', '(.*)'\);/", $contents, $matches))

        return trim($matches[1]);
    else
        return "0";
}

function check_phpmyadmin_remote($contents)
{
    if ($contents) {
        preg_match("/PMA_latest_version = '(.*)'/", $contents, $matches);

        return trim($matches[1]);
    }

    return "0";
}

function check_checker_local()
{
    $contents = read_file("VERSION");
    if(!$contents) return "0";

    return trim($contents);
}

function check_checker_remote($contents)
{
    if ($contents) {
        return trim($contents);
    }

    return "0";
}

function check_dotclear_local($url)
{
    $contents = read_file($url."/inc/prepend.php");
    if(!$contents) return "0";

    if(preg_match("/define\('DC_VERSION','(.*)'\);/", $contents, $matches))

        return trim($matches[1]);
    else
        return "0";
}

function check_dotclear_remote($contents)
{
    if ($contents) {
        if(preg_match("/name=\"stable\" version=\"(.*)\"/", $contents, $matches))

            return trim($matches[1]);
        else
            return "0";
    }

    return "0";
}

function check_gitlab_local($url)
{
    $contents = read_file($url."/VERSION");
    if(!$contents) return "0";

    return trim($contents);
}

function check_gitlab_remote($contents)
{
    if ($contents) {
        return trim($contents);
    }

    return "0";
}

function check_symfony_local($url)
{
    $contents = read_file($url."/composer.lock");
    if(!$contents) return "0";

    if(preg_match('/"name": "symfony\/symfony",'."\n".'(.*)"version": "v(.*)",/', $contents, $matches))

        return trim($matches[2]);
    else
        return "0";
}

function check_symfony_remote($contents)
{
    if(!$contents) return "0";

    $json = json_decode($contents);
    $package = $json->{'packages'};

    return get_last_version_packagist($package);
}

function check_wordpress_local($url)
{
    $contents = read_file($url."/wp-includes/version.php");
    if(!$contents) return "0";

    if(preg_match("/wp_version = '(.*)';/", $contents, $matches))

        return trim($matches[1]);
    else
        return "0";
}

function check_wordpress_remote($contents)
{
    if ($contents) {
        $wordpress_array = unserialize($contents);

        return trim($wordpress_array['offers'][0]['current']);
    }

    return "0";
}

function check_pluxml_local($url)
{
    $contents = read_file($url."/version");
    if(!$contents) return "0";

    return trim($contents);
}

function check_pluxml_remote($contents)
{
    if ($contents) {
        return trim($contents);
    }

    return "0";
}

function get_last_version_packagist($package)
{
    $last_v = "0.0.0.0";
    foreach ($package as $property=>$value) {
        foreach ($value as $p=>$v) {
            $parser = new VersionParser();

            if ($parser->parseStability($p) == "stable") {
                $version = $parser->normalize($p);
                $current = explode(".", $version);
                $last = explode(".", $last_v);

                if ($current[0] > $last[0]) {
                    $last_v = $version;
                } elseif ($current[0] == $last[0]) {
                    if ($current[1] > $last[1]) {
                        $last_v = $version;
                    } elseif ($current[1] == $last[1]) {
                        if ($current[2] > $last[2]) {
                            $last_v = $version;
                        }
                    }
                }
            }
        }
    }
    $tmp = explode(".",$last_v);
    $version = $tmp[0].".".$tmp[1].".".$tmp[2];

    return $version;
}
