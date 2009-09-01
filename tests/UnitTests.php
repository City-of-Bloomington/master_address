<?php
require_once 'PHPUnit/Framework.php';

class UnitTests extends PHPUnit_Framework_TestSuite
{
    public static function suite()
    {
        $suite = new UnitTests('Unit Tests');

		return $suite;
	}
}
