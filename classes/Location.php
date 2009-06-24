<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
class Location
{
	private $id;
	private $location_id;
	private $location_type_id;
	private $street_address_id;
	private $subunit_id;
	private $mailable_flag;
	private $livable_flag;
	private $common_name;
	private $active;


	private $location;
	private $locationType;
	private $address;
	private $subunit;



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
				$sql = 'select * from address_location where id=?';
				$result = $zend_db->fetchRow($sql,array($id));
			}

			if ($result) {
				foreach ($result as $field=>$value) {
					if ($value) {
						$this->$field = $value;
					}
				}
			}
			else {
				throw new Exception('locations/unknownLocation');
			}
		}
		else {
			// This is where the code goes to generate a new, empty instance.
			// Set any default values for properties that need it here
			$this->active = 'Y';
		}
	}

	/**
	 * Throws an exception if anything's wrong
	 * @throws Exception $e
	 */
	public function validate()
	{
		// Check for required fields here.  Throw an exception if anything is missing.
		if (!$this->location_id || !$this->street_address_id
			|| !$this->location_type_id || !$this->active) {
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
		$data['location_id'] = $this->location_id;
		$data['location_type_id'] = $this->location_type_id;
		$data['street_address_id'] = $this->street_address_id;
		$data['subunit_id'] = $this->subunit_id ? $this->subunit_id : null;
		$data['mailable_flag'] = $this->mailable_flag ? $this->mailable_flag : null;
		$data['livable_flag'] = $this->livable_flag ? $this->livable_flag : null;
		$data['common_name'] = $this->common_name ? $this->common_name : null;
		$data['active'] = $this->active;

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
		$zend_db->update('address_location',$data,"id='{$this->id}'");
	}

	private function insert($data)
	{
		$zend_db = Database::getConnection();
		$zend_db->insert('address_location',$data);
		if (Database::getType()=='oracle') {
			$this->id = $zend_db->lastSequenceId('location_id_seq');
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
	public function getLocation_id()
	{
		return $this->location_id;
	}

	/**
	 * @return string
	 */
	public function getLocation_type_id()
	{
		return $this->location_type_id;
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
	public function getSubunit_id()
	{
		return $this->subunit_id;
	}

	/**
	 * @return number
	 */
	public function getMailable_flag()
	{
		return $this->mailable_flag;
	}

	/**
	 * @return number
	 */
	public function getLivable_flag()
	{
		return $this->livable_flag;
	}

	/**
	 * @return string
	 */
	public function getCommon_name()
	{
		return $this->common_name;
	}

	/**
	 * @return char
	 */
	public function getActive()
	{
		return $this->active;
	}


	/**
	 * @return LocationType
	 */
	public function getLocationType()
	{
		if ($this->location_type_id) {
			if (!$this->locationType) {
				$this->locationType = new LocationType($this->location_type_id);
			}
			return $this->locationType;
		}
		return null;
	}

	/**
	 * @return Address
	 */
	public function getAddress()
	{
		if ($this->street_address_id) {
			if (!$this->address) {
				$this->address = new Address($this->street_address_id);
			}
			return $this->address;
		}
		return null;
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
	 * @param string $string
	 */
	public function setLocation_type_id($string)
	{
		$this->locationType = new LocationType($string);
		$this->location_type_id = $this->locationType->getId();
	}

	/**
	 * @param number $number
	 */
	public function setStreet_address_id($number)
	{
		$this->address = new Address($number);
		$this->street_address_id = $address->getId();
	}

	/**
	 * @param number $number
	 */
	public function setSubunit_id($number)
	{
		$this->subunit = new Subunit($number);
		$this->subunit_id = $this->subunit->getId();
	}

	/**
	 * @param number $number
	 */
	public function setMailable_flag($number)
	{
		$this->mailable_flag = $number;
	}

	/**
	 * @param number $number
	 */
	public function setLivable_flag($number)
	{
		$this->livable_flag = $number;
	}

	/**
	 * @param string $string
	 */
	public function setCommon_name($string)
	{
		$this->common_name = trim($string);
	}

	/**
	 * @param char $char
	 */
	public function setActive($char)
	{
		$this->active = $char=='Y' ? 'Y' : 'N';
	}

	/**
	 * @param LocationType $locationType
	 */
	public function setLocationType($locationType)
	{
		$this->location_type_id = $locationType->getId();
		$this->locationType = $locationType;
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
	 * @param Subunit $subunit
	 */
	public function setSubunit($subunit)
	{
		$this->subunit_id = $subunit->getId();
		$this->subunit = $subunit;
	}


	//----------------------------------------------------------------
	// Custom Functions
	// We recommend adding all your custom code down here at the bottom
	//----------------------------------------------------------------
	/**
	 * @return PurposeList
	 */
	public function getPurposes()
	{
		return new PurposeList(array('location_id'=>$this->location_id));
	}

	public function getCityCouncilPurpose()
	{
		$list = new PurposeList(array('location_id'=>$this->location_id,
										'type'=>'CITY COUNCIL DISTRICT'));
		if (count($list)) {
			return $list[0];
		}
	}
}