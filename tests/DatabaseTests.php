<?php
require_once 'PHPUnit/Framework.php';

require_once 'DatabaseTests/LocationDbTest.php';
require_once 'DatabaseTests/UserDbTest.php';

class DatabaseTests extends PHPUnit_Framework_TestSuite
{
	protected function setUp()
	{
	}

	protected function tearDown()
	{
	}

    public static function suite()
    {
        $suite = new DatabaseTests('Database Tests');

		$suite->addTestSuite('LocationDbTest');
		$suite->addTestSuite('UserDbTest');

        return $suite;
    }
}
