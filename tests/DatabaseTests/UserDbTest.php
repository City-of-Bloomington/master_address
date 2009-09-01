<?php
require_once 'PHPUnit/Framework.php';

class UserDbTest extends PHPUnit_Framework_TestCase
{
	protected function setUp()
	{
	}
	public function testLoadByID()
	{
		$user = new User(1);
		$this->assertEquals($user->getUsername(),'inghamn');
	}
}
