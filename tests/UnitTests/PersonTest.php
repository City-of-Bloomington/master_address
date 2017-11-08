<?php
/**
 * @copyright 2014-2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
use Application\People\Person;
use PHPUnit\Framework\TestCase;

class PersonTest extends TestCase
{
	public function testGetFullName()
	{
		$person = new Person();
		$person->setFirstname('First');
		$person->setLastname('Last');
		$this->assertEquals('First Last', $person->getFullname());
	}

	public function testAuthenticationMethodDefaultsToLocal()
	{
		$person = new Person();
		$person->setFirstname('First');
		$person->setLastname('Last');
		$person->setEmail('test@localhost');
		$person->setUsername('test');
		$person->validate();

		$this->assertEquals('local', $person->getAuthenticationMethod());
	}
}
