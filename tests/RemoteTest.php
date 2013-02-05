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
    }
}
