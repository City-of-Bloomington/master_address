<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
class Town
{
	private $town_id;
	private $description;
	private $town_code;

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
	 * @param int|array $town_id
	 */
	public function __construct($town_id=null)
	{
		if ($town_id) {
			if (is_array($town_id)) {
				$result = $town_id;
			}
			else {
				$zend_db = Database::getConnection();
				$sql = 'select * from towns_master where town_id like ?';
				$result = $zend_db->fetchRow($sql,array($town_id));
			}

			if ($result) {
				foreach ($result as $field=>$value) {
					if ($value) {
						$this->$field = $value;
					}
				}
			}
			else {
				throw new Exception('towns/unknownTown');
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
		if (!$this->description || !$this->town_code) {
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
		$data['description'] = $this->description;
		$data['town_code'] = $this->town_code;

		if ($this->town_id) {
			$this->update($data);
		}
		else {
			$this->insert($data);
		}
	}

	private function update($data)
	{
		$zend_db = Database::getConnection();
		$zend_db->update('towns_master',$data,"town_id='{$this->town_id}'");
	}

	private function insert($data)
	{
		$zend_db = Database::getConnection();
		$zend_db->insert('towns_master',$data);
		if (Database::getType()=='oracle') {
			$this->town_id = $zend_db->lastSequenceId('town_id_s');
		}
		else {
			$this->town_id = $zend_db->lastInsertId();
		}
	}

	//----------------------------------------------------------------
	// Generic Getters
	//----------------------------------------------------------------

	/**
	 * @return number
	 */
	public function getTown_id()
	{
		return $this->town_id;
	}

	/**
	 * @return string
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * @return string
	 */
	public function getTown_code()
	{
		return $this->town_code;
	}


	//----------------------------------------------------------------
	// Generic Setters
	//----------------------------------------------------------------

	/**
	 * @param string $string
	 */
	public function setDescription($string)
	{
		$this->description = trim($string);
	}

	/**
	 * @param string $string
	 */
	public function setTown_code($string)
	{
		$this->town_code = trim($string);
	}


	//----------------------------------------------------------------
	// Custom Functions
	// We recommend adding all your custom code down here at the bottom
	//----------------------------------------------------------------
	/**
	 * Alias for getTown_id()
	 *
	 * @return int
	 */
	public function getId()
	{
		return $this->getTown_id();
	}

	/**
	 * Alias for getTown_code()
	 *
	 * @return string
	 */
	public function getCode()
	{
		return $this->getTown_code();
	}
	   
}
