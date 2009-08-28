<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
class LocationStatusChange
{
	private $id;
	private $status_code;
	private $effective_start_date;
	private $location_id;
	private $effective_end_date;

	private $addressStatus;

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
				$sql = 'select * from mast_address_location_status where id=?';
				$result = $zend_db->fetchRow($sql,array($id));
			}

			if ($result) {
				foreach ($result as $field=>$value) {
					if ($value) {
						if (preg_match('/date/',$field) && $value != '0000-00-00') {
							$value = new Date($value);
						}
						$this->$field = $value;
					}
				}
			}
			else {
				throw new Exception('locations/unknownLocationStatusChange');
			}
		}
		else {
			// This is where the code goes to generate a new, empty instance.
			// Set any default values for properties that need it here
			$this->effective_start_date = new Date();
		}
	}

	/**
	 * Throws an exception if anything's wrong
	 * @throws Exception $e
	 */
	public function validate()
	{
		// Check for required fields here.  Throw an exception if anything is missing.
		if (!$this->status_code || !$this->location_id || !$this->effective_start_date) {
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
		$data['status_code'] = $this->status_code;
		$data['effective_start_date'] = $this->effective_start_date->format('Y-m-d');
		$data['location_id'] = $this->location_id;
		$data['effective_end_date'] = $this->effective_end_date ? $this->effective_end_date->format('Y-m-d') : null;

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
		$zend_db->update('mast_address_location_status',$data,"id='{$this->id}'");
	}

	private function insert($data)
	{
		$zend_db = Database::getConnection();
		$zend_db->insert('mast_address_location_status',$data);
		if (Database::getType()=='oracle') {
			$this->id = $zend_db->lastSequenceId('location_status_id_seq');
		}
		else {
			$this->id = $zend_db->lastInsertId();
		}
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
	 * @return number
	 */
	public function getStatus_code()
	{
		return $this->status_code;
	}

	/**
	 * Returns the date/time in the desired format
	 *
	 * Format is specified using PHP's date() syntax
	 * http://www.php.net/manual/en/function.date.php
	 * If no format is given, the Date object is returned
	 *
	 * @param string $format
	 * @return string|DateTime
	 */
	public function getEffective_start_date($format=null)
	{
		if ($format && $this->effective_start_date) {
			return $this->effective_start_date->format($format);
		}
		else {
			return $this->effective_start_date;
		}
	}

	/**
	 * @return number
	 */
	public function getLocation_id()
	{
		return $this->location_id;
	}

	/**
	 * Returns the date/time in the desired format
	 *
	 * Format is specified using PHP's date() syntax
	 * http://www.php.net/manual/en/function.date.php
	 * If no format is given, the Date object is returned
	 *
	 * @param string $format
	 * @return string|DateTime
	 */
	public function getEffective_end_date($format=null)
	{
		if ($format && $this->effective_end_date) {
			return $this->effective_end_date->format($format);
		}
		else {
			return $this->effective_end_date;
		}
	}

	/**
	 * @return AddressStatus
	 */
	public function getStatus()
	{
		if (!$this->addressStatus) {
			$this->addressStatus = new AddressStatus($this->status_code);
		}
		return $this->addressStatus;
	}

	//----------------------------------------------------------------
	// Generic Setters
	//----------------------------------------------------------------

	/**
	 * @param number $number
	 */
	public function setStatus_code($number)
	{
		$this->addressStatus = new AddressStatus($number);
		$this->status_code = $this->addressStatus->getStatus_code();
	}

	/**
	 * Sets the date
	 *
	 * Date arrays should match arrays produced by getdate()
	 *
	 * Date string formats should be in something strtotime() understands
	 * http://www.php.net/manual/en/function.strtotime.php
	 *
	 * @param int|string|array $date
	 */
	public function setEffective_start_date($date)
	{
		if ($date) {
			$this->effective_start_date = new Date($date);
		}
		else {
			$this->effective_start_date = null;
		}
	}

	/**
	 * @param number $number
	 */
	public function setLocation_id($number)
	{
		// $this->location = new Location($number);
		$this->location_id = $number;
	}

	/**
	 * Sets the date
	 *
	 * Date arrays should match arrays produced by getdate()
	 *
	 * Date string formats should be in something strtotime() understands
	 * http://www.php.net/manual/en/function.strtotime.php
	 *
	 * @param int|string|array $date
	 */
	public function setEffective_end_date($date)
	{
		if ($date) {
			$this->effective_end_date = new Date($date);
		}
		else {
			$this->effective_end_date = null;
		}
	}

	/**
	 * @param AddressStatus $addressStatus
	 */
	public function setStatus(AddressStatus $addressStatus)
	{
		$this->status_code = $addressStatus->getStatus_code();
		$this->addressStatus = $addressStatus;
	}

	//----------------------------------------------------------------
	// Custom Functions
	// We recommend adding all your custom code down here at the bottom
	//----------------------------------------------------------------
	public function __toString()
	{
		return $this->getStatus()->__toString();
	}

	/**
	 * Alias for getEffective_start_date()
	 */
	public function getStart_date()
	{
		return $this->getEffective_start_date();
	}

	/**
	 * Alias for getEffective_end_date()
	 */
	public function getEnd_date()
	{
		return $this->getEffective_end_date();
	}

	/**
	 * Alias for setEffective_start_date()
	 */
	public function setStart_date($date)
	{
		$this->setEffective_start_date($date);
	}

	/**
	 * Alias for setEffective_end_date()
	 */
	public function setEnd_date($date)
	{
		$this->setEffective_end_date($date);
	}
}
