<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
class TownshipMaster
{
	private $township_id;
	private $name;
	private $township_abbreviation;
	private $quarter_code;


	private $township;



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
	 * @param int|array $township_id
	 */
	public function __construct($township_id=null)
	{
		if ($township_id) {
			if (is_array($township_id)) {
				$result = $township_id;
			}
			else {
				$zend_db = Database::getConnection();
				$sql = 'select * from township_master where township_id=?';
				$result = $zend_db->fetchRow($sql,array($township_id));
			}

			if ($result) {
				foreach ($result as $field=>$value) {
					if ($value) {
						$this->$field = $value;
					}
				}
			}
			else {
				throw new Exception('township_master/unknownTownshipMaster');
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

	}

	/**
	 * Saves this record back to the database
	 */
	public function save()
	{
		$this->validate();

		$data = array();
		$data['name'] = $this->name ? $this->name : null;
		$data['township_abbreviation'] = $this->township_abbreviation ? $this->township_abbreviation : null;
		$data['quarter_code'] = $this->quarter_code ? $this->quarter_code : null;

		if ($this->township_id) {
			$this->update($data);
		}
		else {
			$this->insert($data);
		}
	}

	private function update($data)
	{
		$zend_db = Database::getConnection();
		$zend_db->update('township_master',$data,"township_id='{$this->township_id}'");
	}

	private function insert($data)
	{
		$zend_db = Database::getConnection();
		$zend_db->insert('township_master',$data);
		$this->id = $zend_db->lastInsertId('township_master','township_id');
	}

	//----------------------------------------------------------------
	// Generic Getters
	//----------------------------------------------------------------

	/**
	 * @return number
	 */
	public function getTownship_id()
	{
		return $this->township_id;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @return char
	 */
	public function getTownship_abbreviation()
	{
		return $this->township_abbreviation;
	}

	/**
	 * @return char
	 */
	public function getQuarter_code()
	{
		return $this->quarter_code;
	}

	/**
	 * @return Township
	 */
	public function getTownship()
	{
		if ($this->township_id) {
			if (!$this->township) {
				$this->township = new Township($this->township_id);
			}
			return $this->township;
		}
		return null;
	}

	//----------------------------------------------------------------
	// Generic Setters
	//----------------------------------------------------------------

	/**
	 * @param string $string
	 */
	public function setName($string)
	{
		$this->name = trim($string);
	}

	/**
	 * @param char $char
	 */
	public function setTownship_abbreviation($char)
	{
		$this->township_abbreviation = $char;
	}

	/**
	 * @param char $char
	 */
	public function setQuarter_code($char)
	{
		$this->quarter_code = $char;
	}

	/**
	 * @param Township $township
	 */
	public function setTownship($township)
	{
		$this->township_id = $township->getId();
		$this->township = $township;
	}


	//----------------------------------------------------------------
	// Custom Functions
	// We recommend adding all your custom code down here at the bottom
	//----------------------------------------------------------------
}
