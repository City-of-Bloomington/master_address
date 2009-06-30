<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
class SubunitStatus
{
	private $subunit_id;
	private $street_address_id;
	private $status_code;
	private $start_date;
	private $end_date;


	private $subunit;
	private $address;

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
	 * @param int|array $subunit_id
	 */
	public function __construct($subunit_id=null)
	{
		if ($subunit_id) {
			if (is_array($subunit_id)) {
				$result = $subunit_id;
			}
			else {
				$zend_db = Database::getConnection();
				$sql = 'select * from mast_address_subunit_status where subunit_id=?';
				$result = $zend_db->fetchRow($sql,array($subunit_id));
			}

			if ($result) {
				foreach ($result as $field=>$value) {
					if ($value) {
					  if (($field=='start_date' || $field=='end_date')
						  && $value != '0000-00-00') {
							$value = new Date($value);
					  }
					  $this->$field = $value;
					}
				}
			}
			else {
				throw new Exception('mast_address_subunit_status/unknownMastAddressSubunitStatus');
			}
		}
		else {
			// This is where the code goes to generate a new, empty instance.
			// Set any default values for properties that need it here
		}
	}

	public function exists(){
	  
	  $zend_db = Database::getConnection();
	  $sql = 'select * from mast_address_subunit_status where subunit_id=?';
	  $result = $zend_db->fetchRow($sql,array($this->subunit_id));
	  if ($result) {
		  return true;
	  }
	  return false;
	}
	/**
	 * Throws an exception if anything's wrong
	 * @throws Exception $e
	 */
	public function validate()
	{
		// Check for required fields here.  Throw an exception if anything is missing.
	  if(!$this->subunit_id){
		  throw new Exception('missing_fields');
	  }
	  
	}

	/**
	 * Saves this record back to the database
	 */
	public function save()
	{
		$this->validate();

		$data = array();
		$data['subunit_id'] = $this->subunit_id; // primary key pre-assinged
		$data['street_address_id'] = $this->street_address_id ? $this->street_address_id : null;
		$data['status_code'] = $this->status_code ? $this->status_code : null;
		$data['start_date'] = $this->start_date ? $this->start_date->format('n/j/Y') : null;
		$data['end_date'] = $this->end_date ? $this->end_date->format('n/j/Y') : null;
		if($this->exists()){
			$this->update($data);
		}
		else {
			$this->insert($data);
		}
	}

	private function update($data)
	{
		$zend_db = Database::getConnection();
		$zend_db->update('mast_address_subunit_status',$data,"subunit_id='{$this->subunit_id}'");
	}

	private function insert($data)
	{
		$zend_db = Database::getConnection();
		$zend_db->insert('mast_address_subunit_status',$data);
	}

	//----------------------------------------------------------------
	// Generic Getters
	//----------------------------------------------------------------

	/**
	 * @return number
	 */
	public function getSubunit_id()
	{
		return $this->subunit_id;
	}

	/**
	 * @return number
	 */
	public function getId()
	{
		return $this->subunit_id;
	}

	
	public function setSubunit_id($val)
	{
	  $this->subunit_id = $val;
	}
	/**
	 * @return number
	 */
	public function getStreet_address_id()
	{
		return $this->street_address_id;
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
	 * Format can be specified using either the strftime() or the date() syntax
	 *
	 * @param string $format
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
	 * Format can be specified using either the strftime() or the date() syntax
	 *
	 * @param string $format
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
	 * @return Street_address
	 */
	public function getAddress()
	{
		if ($this->street_address_id) {
			if (!$this->street_address) {
				$this->address = new Address($this->street_address_id);
			}
			return $this->address;
		}
		return null;
	}

	//----------------------------------------------------------------
	// Generic Setters
	//----------------------------------------------------------------

	/**
	 * @param number $number
	 */
	public function setStreet_address_id($number)
	{
		$this->street_address_id = $number;
	}

	/**
	 * @param number $number
	 */
	public function setStatus_code($number)
	{
		$this->status_code = $number;
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
	 * Dates and times should be stored as timestamps internally.
	 * This accepts dates and times in multiple formats and sets the internal timestamp
	 * Accepted formats are:
	 * 		array - in the form of PHP getdate()
	 *		timestamp
	 *		string - anything strtotime understands
	 * @param date $date
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
	 * @param Street_address $street_address
	 */
	public function setAddress($address)
	{
		$this->street_address_id = $address->getId();
		$this->street_address = $address;
	}


	//----------------------------------------------------------------
	// Custom Functions
	// We recommend adding all your custom code down here at the bottom
	//----------------------------------------------------------------

	
}
