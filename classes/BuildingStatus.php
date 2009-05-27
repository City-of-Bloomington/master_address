<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
class BuildingStatus
{
	private $status_code;
	private $description;

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
	 * @param int|array $status_code
	 */
	public function __construct($status_code=null)
	{
		if ($status_code) {
			if (is_array($status_code)) {
				$result = $status_code;
			}
			else {
				$zend_db = Database::getConnection();
				$sql = 'select * from buildings_status_lookup where status_code=?';
				$result = $zend_db->fetchRow($sql,array($status_code));
			}

			if ($result) {
				foreach ($result as $field=>$value) {
					if ($value) {
						$this->$field = $value;
					}
				}
			}
			else {
				throw new Exception('buildings_status/unknownBuildingsStatusLookup');
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
		if (!$this->description) {
			throw new Exception('missingRequriedFields');
		}

	}

	/**
	 * Saves this record back to the database
	 */
	public function save()
	{
		$this->validate();

		$data = array();
		$data['description'] = $this->description ? $this->description : null;

		if ($this->status_code) {
			$this->update($data);
		}
		else {
			$this->insert($data);
		}
	}

	private function update($data)
	{
		$zend_db = Database::getConnection();
		$zend_db->update('buildings_status_lookup',$data,"status_code='{$this->status_code}'");
	}

	private function insert($data)
	{
		$zend_db = Database::getConnection();
		$zend_db->insert('buildings_status_lookup',$data);
		$this->status_code = $zend_db->lastInsertId('buildings_status_lookup','status_code');
		if (Database::getType()=='oracle') {
			$this->status_code = $zend_db->lastSequenceId('buildings_status_code_seq');
		}
		else {
			$this->status_code = $zend_db->lastInsertId();
		}
		
	}

	//----------------------------------------------------------------
	// Generic Getters
	//----------------------------------------------------------------

	/**
	 * @return number
	 */
	public function getStatus_code()
	{
		return $this->status_code;
	}

	/**
	 * @return string
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * @return number
	 */
	public function getId()
	{
		return $this->status_code;
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


	//----------------------------------------------------------------
	// Custom Functions
	// We recommend adding all your custom code down here at the bottom
	//----------------------------------------------------------------
}
