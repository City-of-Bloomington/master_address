<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
class SubunitStatusChange
{
	private $id;
	private $subunit_id;
	private $street_address_id;
	private $status_code;
	private $start_date;
	private $end_date;

	private $subunit;
	private $address;
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
				$sql = 'select * from mast_address_subunit_status where id=?';
				$result = $zend_db->fetchRow($sql,array($id));
			}

			if ($result) {
				foreach ($result as $field=>$value) {
					if ($value) {
						if (preg_match('/date/',$field) && !preg_match('/0000/',$value)) {
							$value = new Date($value);
						}
						$this->$field = $value;
					}
				}
			}
			else {
				throw new Exception('subinits/unknownStatusChange');
			}
		}
		else {
			// This is where the code goes to generate a new, empty instance.
			// Set any default values for properties that need it here
			$this->start_date = new Date();
		}
	}

	/**
	 * Throws an exception if anything's wrong
	 * @throws Exception $e
	 */
	public function validate()
	{
		// Check for required fields here.  Throw an exception if anything is missing.
		if (!$this->subunit_id || !$this->street_address_id
			|| !$this->status_code || !$this->start_date) {
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
		$data['subunit_id'] = $this->subunit_id;
		$data['street_address_id'] = $this->street_address_id;
		$data['status_code'] = $this->status_code;
		$data['start_date'] = $this->start_date->format('Y-m-d H:i:s');
		$data['end_date'] = $this->end_date ? $this->end_date->format('Y-m-d H:i:s') : null;

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
		$zend_db->update('mast_address_subunit_status',$data,"id='{$this->id}'");
	}

	private function insert($data)
	{
		$zend_db = Database::getConnection();
		$zend_db->insert('mast_address_subunit_status',$data);
		if (Database::getType()=='oracle') {
			$this->id = $zend_db->lastSequenceId('subunit_status_id_seq');
		}
		else {
			$this->id = $zend_db->lastInsertId();
		}
	}

	//----------------------------------------------------------------
	// Generic Getters
	//----------------------------------------------------------------
	/**
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @return int
	 */
	public function getSubunit_id()
	{
		return $this->subunit_id;
	}

	/**
	 * @return int
	 */
	public function getStreet_address_id()
	{
		return $this->street_address_id;
	}

	/**
	 * @return int
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
	public function getStart_date($format=null)
	{
		if ($format && $this->start_date) {
			return $this->start_date->format($format);
		}
		else {
			return $this->start_date;
		}
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
	public function getEnd_date($format=null)
	{
		if ($format && $this->end_date) {
			return $this->end_date->format($format);
		}
		else {
			return $this->end_date;
		}
	}


	/**
	 * @return Subunit
	 */
	public function getSubunit()
	{
		if ($this->subunit_id) {
			if (!$this->subunit) {
				$this->subunit = new Subunit($this->subunit_id);
			}
			return $this->subunit;
		}
		return null;
	}

	/**
	 * @return Address
	 */
	public function getAddress()
	{
		if (!$this->address) {
			$this->address = new Address($this->street_address_id);
		}
		return $this->address;
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
	 * @param int $number
	 */
	public function setSubunit_id($number)
	{
		$this->subunit_id = $number;
	}

	/**
	 * @param int $int
	 */
	public function setStreet_address_id($int)
	{
		$this->address = new Address($int);
		$this->street_address_id = $this->address->getId();
	}

	/**
	 * @param int $status_code
	 */
	public function setStatus_code($status_code)
	{
		$this->addressStatus = new AddressStatus($status_code);
		$this->status_code = $addressStatus->getStatus_code();
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
	public function setStart_date($date)
	{
		if ($date) {
			$this->start_date = new Date($date);
		}
		else {
			$this->start_date = null;
		}
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
	public function setEnd_date($date)
	{
		if ($date) {
			$this->end_date = new Date($date);
		}
		else {
			$this->end_date = null;
		}
	}

	/**
	 * @param Subunit $subunit
	 */
	public function setSubunit($subunit)
	{
		$this->subunit_id = $subunit->getId();
		$this->subunit = $subunit;
	}

	/**
	 * @param Address $address
	 */
	public function setAddress($address)
	{
		$this->street_address_id = $address->getId();
		$this->address = $address;
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
}
