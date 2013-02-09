<?php

require_once("config_probe.php");
require_once("functions.php");
require_once("remote_url.php");

$json_version = array();

//check all local version
foreach ($hosts as $name=>$url) {
    $json_version[$name]['local'] = call_user_func("check_".strtolower($name)."_local",$url);
}

//build an array for doing a multi curl request
foreach ($hosts as $name=>$url) {
    if(array_key_exists(strtolower($name),$remote_url))
        $remote[] = $remote_url[strtolower($name)];
}

//call multi_curl request
$remote_content = get_nodes($remote);

//check all remote version
$i=0;
foreach ($hosts as $name=>$url) {
    if(array_key_exists(strtolower($name),$remote_url)) {
        $json_version[$name]['remote'] = call_user_func("check_".strtolower($name)."_remote", $remote_content[$i]);
        $i++;
    }
    else {
        $json_version[$name]['remote'] = call_user_func("check_".strtolower($name)."_remote");
    }
    
}

echo json_encode(array($json_version));

?>
