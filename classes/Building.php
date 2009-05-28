<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
class Building
{
	private $building_id;
	private $building_type_id;
	private $gis_tag;
	private $building_name;
	private $effective_start_date;
	private $effective_end_date;
	private $status_code;

	private $buildingType;
	private $buildingStatus;

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
	 * @param int|array $building_id
	 */
	public function __construct($building_id=null)
	{
		if ($building_id) {
			if (is_array($building_id)) {
				$result = $building_id;
			}
			else {
				$zend_db = Database::getConnection();
				$sql = 'select * from buildings where building_id=?';
				$result = $zend_db->fetchRow($sql,array($building_id));
			}

			if ($result) {
				foreach ($result as $field=>$value) {
					if ($value) {
						$this->$field = $value;
					}
				}
			}
			else {
				throw new Exception('buildings/unknownBuilding');
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
		if (!$this->building_name) {
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
		$data['building_type_id'] = $this->building_type_id ? $this->building_type_id : null;
		$data['gis_tag'] = $this->gis_tag ? $this->gis_tag : null;
		$data['building_name'] = $this->building_name ? $this->building_name : null;
		$data['effective_start_date'] = $this->effective_start_date ? $this->effective_start_date : null;
		$data['effective_end_date'] = $this->effective_end_date ? $this->effective_end_date : null;
		$data['status_code'] = $this->status_code ? $this->status_code : null;

		if ($this->building_id) {
			$this->update($data);
		}
		else {
			$this->insert($data);
		}
	}

	private function update($data)
	{
		$zend_db = Database::getConnection();
		$zend_db->update('buildings',$data,"building_id='{$this->building_id}'");
	}

	private function insert($data)
	{
		$zend_db = Database::getConnection();
		$zend_db->insert('buildings',$data);
		if (Database::getType()=='oracle') {
		  $this->building_id = $zend_db->lastSequenceId('building_id_s');
		}
		else {
		  $this->building_id = $zend_db->lastInsertId('buildings','building_id');
		}
	}

	//----------------------------------------------------------------
	// Generic Getters
	//----------------------------------------------------------------

	/**
	 * @return number
	 */
	public function getBuilding_id()
	{
		return $this->building_id;
	}

	/**
	 * @return number
	 */
	public function getBuilding_type_id()
	{
		return $this->building_type_id;
	}

	/**
	 * @return string
	 */
	public function getGis_tag()
	{
		return $this->gis_tag;
	}

	/**
	 * @return string
	 */
	public function getBuilding_name()
	{
		return $this->building_name;
	}

	/**
	 * Returns the date/time in the desired format
	 * Format can be specified using either the strftime() or the date() syntax
	 *
	 * @param string $format
	 */
	public function getEffective_start_date($format=null)
	{
		if ($format && $this->effective_start_date) {
			if (strpos($format,'%')!==false) {
				return strftime($format,$this->effective_start_date);
			}
			else {
				return date($format,$this->effective_start_date);
			}
		}
		else {
			return $this->effective_start_date;
		}
	}

	/**
	 * Returns the date/time in the desired format
	 * Format can be specified using either the strftime() or the date() syntax
	 *
	 * @param string $format
	 */
	public function getEffective_end_date($format=null)
	{
		if ($format && $this->effective_end_date) {
			if (strpos($format,'%')!==false) {
				return strftime($format,$this->effective_end_date);
			}
			else {
				return date($format,$this->effective_end_date);
			}
		}
		else {
			return $this->effective_end_date;
		}
	}

	/**
	 * @return number
	 */
	public function getStatus_code()
	{
		return $this->status_code;
	}

	/**
	 * @return BuildingType
	 */
	public function getBuildingType()
	{
		if ($this->building_type_id) {
			if (!$this->buildingType) {
				$this->buildingType = new BuildingType($this->building_type_id);
			}
			return $this->buildingType;
		}
		return null;
	}
	/**
	 * @return BuildingStatus
	 */
	public function getBuildingStatus()
	{
		if ($this->status_code) {
			if (!$this->buildingStatus) {
				$this->buildingStatus = new BuildingStatus($this->status_code);
			}
			return $this->buildingStatus;
		}
		return null;
	}
	
	//----------------------------------------------------------------
	// Generic Setters
	//----------------------------------------------------------------

	/**
	 * @param number $number
	 */
	public function setBuilding_type_id($number)
	{
		$this->building_type_id = $number;
	}

	/**
	 * @param string $string
	 */
	public function setGis_tag($string)
	{
		$this->gis_tag = trim($string);
	}

	/**
	 * @param string $string
	 */
	public function setBuilding_name($string)
	{
		$this->building_name = trim($string);
	}

	/**
	 * Sets the date
	 *
	 * Dates and times should be stored as timestamps internally.
	 * This accepts dates and times in multiple formats and sets the internal timestamp
	 * Accepted formats are:
	 * 		array - in the form of PHP getdate()
	 *		timestamp
	 *		string - anything strtotime understands
	 * @param date $date
	 */
	public function setEffective_start_date($date)
	{
		if (is_array($date)) {
			$this->effective_start_date = $this->dateArrayToTimestamp($date);
		}
		elseif (ctype_digit($date)) {
			$this->effective_start_date = $date;
		}
		else {
			$this->effective_start_date = strtotime($date);
		}
	}

	/**
	 * Sets the date
	 *
	 * Dates and times should be stored as timestamps internally.
	 * This accepts dates and times in multiple formats and sets the internal timestamp
	 * Accepted formats are:
	 * 		array - in the form of PHP getdate()
	 *		timestamp
	 *		string - anything strtotime understands
	 * @param date $date
	 */
	public function setEffective_end_date($date)
	{
		if (is_array($date)) {
			$this->effective_end_date = $this->dateArrayToTimestamp($date);
		}
		elseif (ctype_digit($date)) {
			$this->effective_end_date = $date;
		}
		else {
			$this->effective_end_date = strtotime($date);
		}
	}

	/**
	 * @param number $number
	 */
	public function setStatus_code($number)
	{
		$this->status_code = $number;
	}


	/**
	 * @param BuildingType $buildingType
	 */
	public function setBuildingType($buildingType)
	{
		$this->building_type_id = $buildingType->getId();
		$this->buildingType = $buildingType;
	}

	/**
	 * @param BuildingStatus $buildingStatus
	 */
	public function setBuildingStatus($buildingStatus)
	{
		$this->status_code = $buildingStatus->getId();
		$this->buildingStatus = $buildingStatus;
	}
	//----------------------------------------------------------------
	// Custom Functions
	// We recommend adding all your custom code down here at the bottom
	//----------------------------------------------------------------
}
