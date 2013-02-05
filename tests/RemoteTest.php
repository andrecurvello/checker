<?php 

require_once 'probe/probe.php';

class remoteTest extends PHPUnit_Framework_TestCase {

    protected function setUp() {
    }

    protected function tearDown() {
    }

    public function testRemotePiwigo() {
        $this->assertRegExp("/(.*)\.(.*)\.(.*)/",check_piwigo_remote());
        $this->assertRegExp("/(.*)\.(.*)\.(.*)/",check_owncloud_remote());
        $this->assertRegExp("/(.*)\.(.*)\.(.*)/",check_phpsysinfo_remote());
        $this->assertRegExp("/(.*)\.(.*)\.(.*)/",check_mediawiki_remote());
        //$this->assertRegExp("/(.*)\.(.*)\.(.*)/",check_dokuwiki_local());
        $this->assertRegExp("/(.*)\.(.*)\.(.*)/",check_phpmyadmin_remote());
        $this->assertRegExp("/(.*)\.(.*)\.(.*)/",check_wordpress_remote());
        $this->assertRegExp("/(.*)\.(.*)\.(.*)/",check_dotclear_remote());
        $this->assertRegExp("/(.*)\.(.*)\.(.*)/",check_gitlab_remote());
        $this->assertRegExp("/(.*)\.(.*)\.(.*)/",check_symfony_remote());
        $this->assertRegExp("/(.*)\.(.*)\.(.*)/",check_checker_remote());
    }
}
