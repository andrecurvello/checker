<?php 

require_once("probe/functions.php");
require_once("probe/remote_url.php");

class remoteTest extends PHPUnit_Framework_TestCase {

    protected function setUp() {


    }

    protected function tearDown() {

    }

    public function testRemote() {

        $remote_url = get_remote_url();
        //build an array for doing a multi curl request
        foreach ($remote_url as $name=>$url) {
                $remote[] = $remote_url[strtolower($name)];
        }

        //call multi_curl request
        $remote_content = get_nodes($remote);

        $this->assertRegExp("/(.*)\.(.*)\.(.*)/", check_piwigo_remote($remote_content[0]));
        $this->assertRegExp("/(.*)\.(.*)\.(.*)/", check_owncloud_remote($remote_content[1]));
        $this->assertRegExp("/(.*)\.(.*)\.(.*)/", check_phpsysinfo_remote($remote_content[2]));
        $this->assertRegExp("/(.*)\.(.*)\.(.*)/", check_mediawiki_remote());
        $this->assertRegExp("/(.*)\-(.*)\-(.*)/", check_dokuwiki_remote($remote_content[3]));
        $this->assertRegExp("/(.*)\.(.*)\.(.*)/", check_phpmyadmin_remote($remote_content[4]));
        $this->assertRegExp("/(.*)\.(.*)\.(.*)/", check_checker_remote($remote_content[5]));
        $this->assertRegExp("/(.*)\.(.*)\.(.*)/", check_dotclear_remote($remote_content[6]));
        $this->assertRegExp("/(.*)\.(.*)\.(.*)/", check_gitlab_remote($remote_content[7]));
        $this->assertRegExp("/(.*)\.(.*)\.(.*)/", check_pluxml_remote($remote_content[8]));
        $this->assertRegExp("/(.*)\.(.*)\.(.*)/", check_wordpress_remote($remote_content[9]));
        $this->assertRegExp("/(.*)\.(.*)\.(.*)/", check_symfony_remote($remote_content[10]));

    }
}
