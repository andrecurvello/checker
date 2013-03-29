<?php

set_time_limit(0);

//settings
$frequency = 1; //minutes
$page = "http://localhost/~rk4an/checker/probe/probe.php";

//notifications by email
$email = "***";
$title = "New update detect by checker";

$apps = array();

while(1)
{
    //get the page
    $content = file_get_contents($page);
    $json = json_decode($content);

    //build array of remote version and check
    foreach ($json[0] as $app_name => $app_version) {
        echo  $app_name . " : " . $app_version->{'remote'} . "\n";
        if(array_key_exists($app_name,$apps)) {
            if($apps[$app_name] =! $app_version->{'remote'}) {
                mail($email,$title,$title);
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
