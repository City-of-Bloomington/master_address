<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
class Precinct
{
	private $id;
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
	 * @param int|array $id
	 */
	public function __construct($id=null)
	{
		if ($id) {
			if (is_array($id)) {
				$result = $id;
			}
			else {
				$zend_db = Database::getConnection();
				if (ctype_digit($id)) {
					$sql = 'select * from voting_precincts where id=?';
				}
				else {
					$sql = 'select * from voting_precincts where precinct=?';
				}
				$result = $zend_db->fetchRow($sql,array($id));
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
		$data['precinct'] = $this->precinct;
		$data['precinct_name'] = $this->precinct_name;
		$data['active'] = $this->active;

		if ($this->id) {
			$this->update($data);
		}
		else {
			$this->insert($data);
		}
	}

	private function update($data)
	{
		$zend_db = Database::getConnection();
		$zend_db->update('voting_precincts',$data,"id='{$this->id}'");
	}

	private function insert($data)
	{
		$zend_db = Database::getConnection();
		$zend_db->insert('voting_precincts',$data);
		$this->id = $zend_db->lastInsertId('voting_precincts','id');
	}

	//----------------------------------------------------------------
	// Generic Getters
	//----------------------------------------------------------------
	/**
	 * @return number
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getPrecinct()
	{
		return $this->precinct;
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
		return $this->active=='Y' ? 'Y' : 'N';
	}


	//----------------------------------------------------------------
	// Generic Setters
	//----------------------------------------------------------------

	/**
	 * @param string $string
	 */
	public function setPrecinct($string)
	{
		$this->precinct = trim($string);
	}

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
