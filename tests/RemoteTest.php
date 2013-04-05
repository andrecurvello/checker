<?php

require_once 'probe/functions.php';
require_once 'probe/remote_url.php';

class remoteTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
    }

    protected function tearDown()
    {
    }

    public function testRemote()
    {
        $remote_url = get_remote_url();
        //build an array for doing a multi curl request
        foreach ($remote_url as $name=>$url) {
                $remote[] = $remote_url[strtolower($name)];
        }

        //call multi_curl request
        $remote_content = get_nodes($remote);
        $default_regex = "/^[0-9]+(\.[0-9]+){0,3}$/";

        $this->assertRegExp($default_regex, check_piwigo_remote($remote_content[0]));
        $this->assertRegExp($default_regex, check_owncloud_remote($remote_content[1]));
        $this->assertRegExp($default_regex, check_phpsysinfo_remote($remote_content[2]));
        $this->assertRegExp($default_regex, check_mediawiki_remote($remote_content[11]));
        //$this->assertRegExp($default_regex, check_dokuwiki_remote($remote_content[3]));
        $this->assertRegExp($default_regex, check_phpmyadmin_remote($remote_content[4]));
        $this->assertRegExp($default_regex, check_checker_remote($remote_content[5]));
        $this->assertRegExp($default_regex, check_dotclear_remote($remote_content[6]));
        $this->assertRegExp($default_regex, check_gitlab_remote($remote_content[7]));
        $this->assertRegExp($default_regex, check_pluxml_remote($remote_content[8]));
        $this->assertRegExp($default_regex, check_wordpress_remote($remote_content[9]));
        $this->assertRegExp($default_regex, check_symfony_remote($remote_content[10]));

    }
}
