<?php

require_once("functions.php");
require_once("config_probe.php");

$json_version = array();

foreach ($hosts as $name=>$url) {
    $json_version[$name]['local'] = call_user_func("check_".strtolower($name)."_local",$url);
    $json_version[$name]['remote'] = call_user_func("check_".strtolower($name)."_remote");
}

echo json_encode(array($json_version));

?>
