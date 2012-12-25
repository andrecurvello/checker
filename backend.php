<?php

/******************************CONFIGURATION***************************/

//Piwigo
$piwigo_local = "/home/user/public_html/piwigo";

//Owncloud
$oc_local = "/home/user/public_html/owncloud";

//phpSysInfo
$phpsysinfo_local = "/home/user/public_html/phpsysinfo";

//MediaWiki
$mediawiki_local = "/home/user/public_html/mediawiki";

/**********************************************************************/
$all_version = array();


//Piwigo
$result = file_get_contents("http://piwigo.org/download/all_versions.php");
$all_piwigo_versions = @explode("\n", $result);
$new_piwigo_version = trim($all_piwigo_versions[0]);

$handle = fopen($piwigo_local."/include/constants.php", "rb");
$contents = '';
while (!feof($handle)) { $contents .= fread($handle, 8192);}
fclose($handle);

preg_match("/define\('PHPWG_VERSION', '(.*)'\);/", $contents, $matches);

$piwigo['Piwigo']['local'] = $matches[1];
$piwigo['Piwigo']['remote'] = $new_piwigo_version;

$all_version[] = $piwigo;


//Owncloud
include($oc_local."/lib/util.php");

$updaterurl='http://apps.owncloud.com/updater.php';
$version=OC_Util::getVersion();
$version['installed']='';
$version['updated']='';
$version['updatechannel']='stable';
$version['edition']="";
$versionstring=implode('x',$version);

$url=$updaterurl.'?version='.$versionstring;

$ctx = stream_context_create(array( 'http' => array( 'timeout' => 10 )) ); 
$xml = @file_get_contents($url, 0, $ctx);
$data = @simplexml_load_string($xml);

$tmp = array();
$tmp['version'] = $data->version;
$tmp['versionstring'] = $data->versionstring;
$tmp['url'] = $data->url;
$tmp['web'] = $data->web;

if($tmp['versionstring'] == "") { $tmp['versionstring'] = OC_Util::getVersionString(); }

$ocs['OwnCloud']['local'] = OC_Util::getVersionString();
$ocs['OwnCloud']['remote'] = $tmp['versionstring'];

$all_version[] = $ocs;


//phpSysInfo
include($phpsysinfo_local."/includes/class.CommonFunctions.inc.php");

$phpsysinfo['phpSysInfo']['local'] = CommonFunctions::PSI_VERSION;

//get latest tag of a local repo
//(but you are not sure if dir is a git repo and the latest tag are stable tag, and we need to fetch before)
//exec("cd ". $phpsysinfo_local . " && git describe", $output);
//$phpsysinfo['phpSysInfo']['remote'] = $output;

//get latest file for master branch on git
$psi_file = file_get_contents("https://raw.github.com/rk4an/phpsysinfo/master/includes/class.CommonFunctions.inc.php");
preg_match("/const PSI_VERSION = '(.*)'/", $psi_file, $matches);
$phpsysinfo['phpSysInfo']['remote'] = $matches[1];

$all_version[] = $phpsysinfo;


//Mediawiki
$handle = fopen($mediawiki_local."/includes/DefaultSettings.php", "rb");
$contents = '';
while (!feof($handle)) { $contents .= fread($handle, 8192); }
fclose($handle);
preg_match("/$wgVersion = '(.*)'/", $contents, $matches);

$mediawiki['MediaWiki']['local'] = $matches[1];

exec("git ls-remote --tags https://gerrit.wikimedia.org/r/p/mediawiki/core.git | cut  -f2 | tr -d 'refs/tags/' | sort -r --version-sort --field-separator=. -k2 | head -n 1", $output);
$mediawiki['MediaWiki']['remote'] = $output;

$all_version[] = $mediawiki;


//Results
echo json_encode($all_version);

?>
