<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
class AddressLocationChange
{
	private $location_change_id;
	private $location_id;
	private $old_location_id;
	private $change_date;
	private $notes;

	private $location;
	private $oldLocation;



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
	 * @param int|array $location_change_id
	 */
	public function __construct($location_change_id=null)
	{
		if ($location_change_id) {
			if (is_array($location_change_id)) {
				$result = $location_change_id;
			}
			else {
				$zend_db = Database::getConnection();
				$sql = 'select * from mast_address_location_change where location_change_id=?';
				$result = $zend_db->fetchRow($sql,array($location_change_id));
			}

			if ($result) {
				foreach ($result as $field=>$value) {
					if ($value) {
						$this->$field = $value;
					}
				}
			}
			else {
				throw new Exception('mast_address_location_change/unknownMastAddressLocationChange');
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
		$data['location_id'] = $this->location_id ? $this->location_id : null;
		$data['old_location_id'] = $this->old_location_id ? $this->old_location_id : null;
		$data['change_date'] = $this->change_date ? $this->change_date : null;
		$data['notes'] = $this->notes ? $this->notes : null;

		if ($this->location_change_id) {
			$this->update($data);
		}
		else {
			$this->insert($data);
		}
	}

	private function update($data)
	{
		$zend_db = Database::getConnection();
		$zend_db->update('mast_address_location_change',$data,"location_change_id='{$this->location_change_id}'");
	}

	private function insert($data)
	{
		$zend_db = Database::getConnection();
		$zend_db->insert('mast_address_location_change',$data);
		if (Database::getType()=='oracle') {
			$this->location_change_id = $zend_db->lastSequenceId('location_chage_id_s');
		}
		else{
		    $this->location_change_id = $zend_db->lastInsertId('mast_address_location_change','location_change_id');
		}
	}

	//----------------------------------------------------------------
	// Generic Getters
	//----------------------------------------------------------------

	/**
	 * @return number
	 */
	public function getLocation_change_id()
	{
		return $this->location_change_id;
	}

	/**
	 * @return number
	 */
	public function getLocation_id()
	{
		return $this->location_id;
	}

	/**
	 * @return number
	 */
	public function getOld_location_id()
	{
		return $this->old_location_id;
	}

	/**
	 * Returns the date/time in the desired format
	 * Format can be specified using either the strftime() or the date() syntax
	 *
	 * @param string $format
	 */
	public function getChange_date($format=null)
	{
		if ($format && $this->change_date) {
			if (strpos($format,'%')!==false) {
				return strftime($format,$this->change_date);
			}
			else {
				return date($format,$this->change_date);
			}
		}
		else {
			return $this->change_date;
		}
	}

	/**
	 * @return string
	 */
	public function getNotes()
	{
		return $this->notes;
	}

	/**
	 * @return Location
	 */
	public function getLocation()
	{
		if ($this->location_id) {
			if (!$this->location) {
				$this->location = new Location($this->location_id);
			}
			return $this->location;
		}
		return null;
	}

	/**
	 * @return Old_location
	 */
	public function getOldLocation()
	{
		if ($this->old_location_id) {
			if (!$this->oldLocation) {
				$this->oldLocation = new Location($this->old_location_id);
			}
			return $this->oldLocation;
		}
		return null;
	}
	//----------------------------------------------------------------
	// Generic Setters
	//----------------------------------------------------------------
	/**
	 * @param number $number
	 */
	public function setLocation_id($number)
	{
		$this->location_id = $number;
	}
	/**
	 * @param number $number
	 */
	public function setOld_location_id($number)
	{
		$this->old_location_id = $number;
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
	public function setChange_date($date)
	{
		if (is_array($date)) {
			$this->change_date = $this->dateArrayToTimestamp($date);
		}
		elseif (ctype_digit($date)) {
			$this->change_date = $date;
		}
		else {
			$this->change_date = strtotime($date);
		}
	}

	/**
	 * @param string $string
	 */
	public function setNotes($string)
	{
		$this->notes = trim($string);
	}

	/**
	 * @param Location $location
	 */
	public function setLocation($location)
	{
		$this->location_id = $location->getId();
		$this->location = $location;
	}

	/**
	 * @param Old_location $old_location
	 */
	public function setOldLocation($oldLocation)
	{
		$this->old_location_id = $oldLocation->getId();
		$this->old_location = $oldLocation;
	}


	//----------------------------------------------------------------
	// Custom Functions
	// We recommend adding all your custom code down here at the bottom
	//----------------------------------------------------------------
	public function __toString(){
	    $ret = '';
		/*
		  // TODO
		  // we need location class here
	    if($this->getLocation()){
		    $ret = $this->getLocation();
	    }
		*/
		if($this->getLocation_id()){
		    $ret = $this->getLocation_id();
	    }
	    $ret .=' '.$this->getChange_date();
	    return $ret;
	}
}
