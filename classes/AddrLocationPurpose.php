<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
class AddrLocationPurpose
{
	private $location_purpose_id;
	private $description;
	private $type;


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
	 * @param int|array $location_purpose_id
	 */
	public function __construct($location_purpose_id=null)
	{
		if ($location_purpose_id) {
			if (is_array($location_purpose_id)) {
				$result = $location_purpose_id;
			}
			else {
				$zend_db = Database::getConnection();
				$sql = 'select * from addr_location_purpose_mast where location_purpose_id=?';
				$result = $zend_db->fetchRow($sql,array($location_purpose_id));
			}

			if ($result) {
				foreach ($result as $field=>$value) {
					if ($value) {
						$this->$field = $value;
					}
				}
			}
			else {
				throw new Exception('addr_location_purpose_mast/unknownAddrLocationPurposeMast');
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
		$data['description'] = $this->description ? $this->description : null;
		$data['type'] = $this->type ? $this->type : null;

		if ($this->location_purpose_id) {
			$this->update($data);
		}
		else {
			$this->insert($data);
		}
	}

	private function update($data)
	{
		$zend_db = Database::getConnection();
		$zend_db->update('addr_location_purpose_mast',$data,"location_purpose_id='{$this->location_purpose_id}'");
	}

	private function insert($data)
	{
		$zend_db = Database::getConnection();
		$zend_db->insert('addr_location_purpose_mast',$data);
	   	if (Database::getType()=='oracle') {
		  $this->location_purpose_id = $zend_db->lastSequenceId('location_purpose_id_s');
		}
		else{
		  $this->location_purpose_id = $zend_db->lastInsertId('addr_location_purpose_mast','location_purpose_id');
		}
	}

	//----------------------------------------------------------------
	// Generic Getters
	//----------------------------------------------------------------

	/**
	 * @return number
	 */
	public function getLocation_purpose_id()
	{
		return $this->location_purpose_id;
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
	public function getType()
	{
		return $this->type;
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
	public function setType($string)
	{
		$this->type = trim($string);
	}

	//----------------------------------------------------------------
	// Custom Functions
	// We recommend adding all your custom code down here at the bottom
	//----------------------------------------------------------------
}
