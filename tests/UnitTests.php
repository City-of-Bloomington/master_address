<?php
require_once 'PHPUnit/Framework.php';
require_once 'UnitTests/URLTest.php';
class UnitTests extends PHPUnit_Framework_TestSuite
{
    public static function suite()
    {
        $suite = new UnitTests('Unit Tests');

		$suite->addTestSuite('URLTest');

		return $suite;
	}
}
