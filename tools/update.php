<?php

set_time_limit(0);

//settings
$frequency = 60; //minutes
$page = "http://localhost/~rk4an/checker/probe/probe.php";
$email = "***";

$title = "Checker: new update for ";
$apps = array();

while(1) {
    //get the page
    $content = file_get_contents($page);
    $json = json_decode($content);

    //build array of remote version and check
    foreach ($json[0] as $app_name => $app_version) {

        echo  $app_name . " : " . $app_version->{'remote'} . "\n";
        
        if(array_key_exists($app_name,$apps)) {
            if($apps[$app_name] != $app_version->{'remote'}) {
                mail($email, $title.$app_name, $title.$app_name);
                $apps[$app_name] = $app_version->{'remote'};
            }
        }
        else {
            $apps[$app_name] = $app_version->{'remote'};
        }
    }

    echo "...\n";
    sleep($frequency*60);
}


?>
