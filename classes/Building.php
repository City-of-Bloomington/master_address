<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
class Building
{
	private $building_id;
	private $buildingType_id;
	private $gis_tag;
	private $buildingName;
	private $effectiveStartDate;
	private $effectiveEndDate;
	private $status_code;

	private $building;
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

	}

	/**
	 * Saves this record back to the database
	 */
	public function save()
	{
		$this->validate();

		$data = array();
		$data['building_type_id'] = $this->buildingType_id ? $this->buildingType_id : null;
		$data['gis_tag'] = $this->gis_tag ? $this->gis_tag : null;
		$data['building_name'] = $this->buildingName ? $this->buildingName : null;
		$data['effective_start_date'] = $this->effectiveStartDate ? $this->effectiveStartDate : null;
		$data['effective_end_date'] = $this->effectiveEndDate ? $this->effectiveEndDate : null;
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
		$this->building_id = $zend_db->lastInsertId('buildings','building_id');
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
	public function getBuildingType_id()
	{
		return $this->buildingType_id;
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
	public function getBuildingName()
	{
		return $this->buildingName;
	}

	/**
	 * Returns the date/time in the desired format
	 * Format can be specified using either the strftime() or the date() syntax
	 *
	 * @param string $format
	 */
	public function getEffectiveStartDate($format=null)
	{
		if ($format && $this->effectiveStartDate) {
			if (strpos($format,'%')!==false) {
				return strftime($format,$this->effectiveStartDate);
			}
			else {
				return date($format,$this->effectiveStartDate);
			}
		}
		else {
			return $this->effectiveStartDate;
		}
	}

	/**
	 * Returns the date/time in the desired format
	 * Format can be specified using either the strftime() or the date() syntax
	 *
	 * @param string $format
	 */
	public function getEffectiveEndDate($format=null)
	{
		if ($format && $this->effectiveEndDate) {
			if (strpos($format,'%')!==false) {
				return strftime($format,$this->effectiveEndDate);
			}
			else {
				return date($format,$this->effectiveEndDate);
			}
		}
		else {
			return $this->effectiveEndDate;
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
	 * @return Building
	 */
	public function getBuilding()
	{
		if ($this->building_id) {
			if (!$this->building) {
				$this->building = new Building($this->building_id);
			}
			return $this->building;
		}
		return null;
	}

	/**
	 * @return Building_type
	 */
	public function getBuildingType()
	{
		if ($this->buildingType_id) {
			if (!$this->buildingType) {
				$this->buildingType = new BuildingType($this->buildingType_id);
			}
			return $this->buildingType;
		}
		return null;
	}

	//----------------------------------------------------------------
	// Generic Setters
	//----------------------------------------------------------------

	/**
	 * @param number $number
	 */
	public function setBuildingType_id($number)
	{
		$this->buildingType_id = $number;
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
	public function setBuildingName($string)
	{
		$this->buildingName = trim($string);
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
	public function setEffectiveStartDate($date)
	{
		if (is_array($date)) {
			$this->effectiveStartDate = $this->dateArrayToTimestamp($date);
		}
		elseif (ctype_digit($date)) {
			$this->effectiveStartDate = $date;
		}
		else {
			$this->effectiveStartDate = strtotime($date);
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
	public function setEffectiveEndDate($date)
	{
		if (is_array($date)) {
			$this->effectiveEndDate = $this->dateArrayToTimestamp($date);
		}
		elseif (ctype_digit($date)) {
			$this->effectiveEndDate = $date;
		}
		else {
			$this->effectiveEndDate = strtotime($date);
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
	 * @param Building $building
	 */
	public function setBuilding($building)
	{
		$this->building_id = $building->getId();
		$this->building = $building;
	}

	/**
	 * @param Building_type $building_type
	 */
	public function setBuildingType($buildingType)
	{
		$this->buildingType_id = $buildingType->getId();
		$this->buildingType = $buildingType;
	}

	/**
	 * @param Building_type $buildingStatus
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
