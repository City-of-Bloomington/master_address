<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
class Precinct
{
	private $precinct;
	private $precinct_name;
	private $active;

	/**
	 * Populates the object with data
	 *
	 * Passing in an associative array of data will populate this object without
	 * hitting the database.
	 *
	 * Passing in a scalar will load the data from the database.
	 * This will load all fields in the table as properties of this class.
	 * You may want to replace this with, or add your own extra, custom loading
	 *
	 * @param int|array $precinct
	 */
	public function __construct($code=null)
	{
		if ($code) {
			if (is_array($code)) {
				$result = $code;
			}
			else {
				$zend_db = Database::getConnection();
				$sql = 'select * from voting_precincts where precinct=?';
				$result = $zend_db->fetchRow($sql,array($code));
			}

			if ($result) {
				foreach ($result as $field=>$value) {
					if ($value) {
						$this->$field = $value;
					}
				}
			}
			else {
				throw new Exception('precincts/unknownPrecinct');
			}
		}
		else {
			// This is where the code goes to generate a new, empty instance.
			// Set any default values for properties that need it here
		}
	}

	/**
	 * Throws an exception if anything's wrong
	 * @throws Exception $e
	 */
	public function validate()
	{
		// Check for required fields here.  Throw an exception if anything is missing.
		if (!$this->precinct || !$this->precinct_name || !$this->active) {
			throw new Exception('missingRequiredFields');
		}

	}

	/**
	 * Saves this record back to the database
	 */
	public function save()
	{
		$this->validate();

		$data = array();
		$data['precinct_name'] = $this->precinct_name;
		$data['active'] = $this->active;

		$zend_db = Database::getConnection();
		$count = $zend_db->fetchOne('select count(*) from voting_precincts where precinct=?',$this->precinct);
		if ($count) {
			$this->update($data);
		}
		else {
			$this->insert($data);
		}
	}

	private function update($data)
	{
		$zend_db = Database::getConnection();
		$zend_db->update('voting_precincts',$data,"precinct='{$this->precinct}'");
	}

	private function insert($data)
	{
		throw new Exception('precincts/insertNotSupported');
	}

	//----------------------------------------------------------------
	// Generic Getters
	//----------------------------------------------------------------
	/**
	 * @return string
	 */
	public function getPrecinct()
	{
		return $this->precinct;
	}

	/**
	 * Alias for getPrecinct()
	 *
	 * Becuase of the naming confusion for precinct.precinct, we're
	 * going to be using the word "code".  This will be an alias
	 *
	 * @return string
	 */
	public function getCode()
	{
		return $this->getPrecinct();
	}

	/**
	 * @return string
	 */
	public function getPrecinct_name()
	{
		return $this->precinct_name;
	}

	/**
	 * @return char
	 */
	public function getActive()
	{
		return $this->active;
	}

	//----------------------------------------------------------------
	// Generic Setters
	//----------------------------------------------------------------

	/**
	 * @param string $string
	 */
	public function setPrecinct_name($string)
	{
		$this->precinct_name = trim($string);
	}

	/**
	 * @param char $char
	 */
	public function setActive($char)
	{
		$this->active = $char=='Y' ? 'Y' : 'N';
	}


	//----------------------------------------------------------------
	// Custom Functions
	// We recommend adding all your custom code down here at the bottom
	//----------------------------------------------------------------
	/**
	 * Alias for getPrecinct_name()
	 *
	 * @return string
	 */
	public function getName()
	{
		return $this->getPrecinct_name();
	}

	/**
	 * @return boolean
	 */
	public function isActive()
	{
		return $this->active=='Y' ? true : false;
	}
}
