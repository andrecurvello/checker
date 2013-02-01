<?php 

require_once 'probe/probe.php';

class remoteTest extends PHPUnit_Framework_TestCase {

    protected function setUp() {
    }

    protected function tearDown() {
    }

    public function testRemotePiwigo() {
        $result = file_get_contents("http://piwigo.org/download/all_versions.php");
        if($result) {
            $all_piwigo_versions = @explode("\n", $result);
            $new_piwigo_version = trim($all_piwigo_versions[0]);
        }
        
        $this->assertRegExp("/(.*)\.(.*)\.(.*)/",$new_piwigo_version);
    }
}
