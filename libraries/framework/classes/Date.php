<?php
/**
 * Helper functions to convert between database types and PHP types
 *
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
class Date
{
	/**
	 * Takes stuff in the form Zend_Date understands and returns a Zend_Date
	 *
	 * Accepted formats are documented here:
	 * http://framework.zend.com/manual/en/zend.date.html
	 * http://framework.zend.com/manual/en/zend.date.creation.html
	 *
	 * @return Zend_Date
	 */
	public static function toZend_Date($stuff)
	{
		// Zend_Date's default parser differs from the ISO_8601 format
		// Zend_Date's default is YYYY-DD-MM and ISO_8601 is YYYY-MM-DD
		// If we're using dashes, then we need to explicitly tell Zend_Date
		// we're using ISO_8601
		if (false !== strpos($stuff,'-')) {
			try {
				$date = new Zend_Date($stuff,Zend_Date::ISO_8601);
			}
			catch (Exception $e) {
				$date = new Zend_Date($stuff);
			}
		}
		else {
			$date = new Zend_Date($stuff);
		}
		return $date;
	}
}