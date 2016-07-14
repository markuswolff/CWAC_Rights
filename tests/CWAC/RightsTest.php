<?php
require_once 'PHPUnit/Framework/TestCase.php';

class RightsTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        require_once __DIR__ . '/../../src/CWAC/Rights.php';
        $rights = array('admin.*',
                        '-admin.bla',
                        'files.open',
                        'files.download.really',
                        '-donotenter.*');
        $user = new CWAC_Rights();
        $user->setRights($rights);
        $this->_user = $user;
    }
    
    public function testCheckExistingRight()
    {
        $this->assertTrue($this->_user->checkRight('files.open'),'files.open');
        $this->assertTrue($this->_user->checkRight('files.download.really'),'files.download.really');
    }
    
    public function testCheckNonExistingRight()
    {
        $this->assertFalse($this->_user->checkRight('files.download.not'),'files.download.not');
    }
    
    public function testCheckPositiveWildcard()
    {
        $this->assertTrue($this->_user->checkRight('admin.honk'),'admin.honk');
    }
    
    public function testCheckRevokedRight()
    {
        $this->assertFalse($this->_user->checkRight('admin.bla'),'admin.bla');
    }
    
    public function testCheckRevokedWildcard()
    {
        $this->assertFalse($this->_user->checkRight('donotenter.this'), 'donotenter.this');
        $this->assertFalse($this->_user->checkRight('donotenter'), 'donotenter');
    }


}
