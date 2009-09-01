<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
class Location
{
	private $lid;
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
	 * @param int|array $lid
	 */
	public function __construct($lid=null)
	{
		if ($lid) {
			if (is_array($lid)) {
				$result = $lid;
			}
			else {
				$zend_db = Database::getConnection();
				$sql = 'select * from address_location where lid=?';
				$result = $zend_db->fetchRow($sql,array($lid));
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
	 * we are cloning this class except for lid paramerter
	 *
	 */
	public function __clone(){
		$this->lid = null;
	}

	/**
	 * Throws an exception if anything's wrong
	 * @throws Exception $e
	 */
	public function validate()
	{
		// Check for required fields here.  Throw an exception if anything is missing.
		if (!$this->street_address_id || !$this->location_type_id || !$this->active) {
			throw new Exception('missingRequiredFields');
		}
	}

	/**
	 * Saves this record back to the database
	 *
	 * @param ChangeLogEntry $changeLogEntry
	 */
	public function save(ChangeLogEntry $changeLogEntry)
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

		if ($this->lid) {
			$this->update($data);
		}
		else {
			$this->insert($data);
		}

		$this->updateChangeLog($changeLogEntry);
	}

	private function update($data)
	{
		$zend_db = Database::getConnection();
		$zend_db->update('address_location',$data,"lid='{$this->lid}'");
	}

	private function insert($data)
	{
		$zend_db = Database::getConnection();

		// Some new locations we want to generate a new location_id
		// as well as a new LID.  First we grab the location_id from it's sequence
		if (!$data['location_id']) {
			$data['location_id'] = $zend_db->lastSequenceId('location_id_s');
			$this->location_id = $data['location_id'];
		}

		$zend_db->insert('address_location',$data);
		if (Database::getType()=='oracle') {
			$this->lid = $zend_db->lastSequenceId('location_lid_seq');
		}
		else {
			throw new Exception('locations/unsupportedDatabaseCall');
		}
	}

	public function updateChangeLog(ChangeLogEntry $changeLogEntry)
	{
		$logEntry = $changeLogEntry->getData();
		$logEntry['lid'] = $this->lid;

		$zend_db = Database::getConnection();
		$zend_db->insert('location_change_log',$logEntry);
	}

	//----------------------------------------------------------------
	// Generic Getters
	//----------------------------------------------------------------
	/**
	 * @return int
	 */
	public function getId()
	{
		return $this->getLid();
	}

	/**
	 * @return int
	 */
	public function getLid()
	{
		return $this->lid;
	}

	/**
	 * @return int
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
	 * Looks up the Address associated with this Location.id
	 *
	 * Not to be confused with looking for addresses with Location.location_id
	 * There will only be one address for each Location.id, but
	 * there will be multiple addresses for each Location.location_id
	 *
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
	 * Looks up all the address having this Location.location_id
	 *
	 * Not to be confused with looking for addresses that have Location.id
	 * There will only be one address per Location.id (see: getAddress())
	 *
	 * @return AddressList
	 */
	public function getAddresses()
	{
		return new AddressList(array('location_id'=>$this->location_id));
	}

	/**
	 * Returns all the locations with this Location.location_id
	 *
	 * Location.location_id is not a unique field.  There can be multiple
	 * locations with the same Location.location_id.
	 */
	public function getLocations(array $fields=null)
	{
		$search = array('location_id'=>$this->location_id);
		if ($fields) {
			$search = array_merge($search,$fields);
		}
		return new LocationList($search);
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
		$this->street_address_id = $this->address->getId();
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
	 * @param boolean bool
	 */
	public function setMailable_flag($bool)
	{

		$this->mailable_flag = $bool?1:0;
	}

	/**
	 * @param boolean bool
	 */
	public function setLivable_flag($bool)
	{
		$this->livable_flag = $bool?1:0;
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
		if ($this->location_id) {
			return new PurposeList(array('location_id'=>$this->location_id));
		}
		return array();
	}

	/**
	 * @return Purpose
	 */
	public function getCityCouncilPurpose()
	{
		$list = new PurposeList(array('location_id'=>$this->location_id,
										'type'=>'CITY COUNCIL DISTRICT'));
		if (count($list)) {
			return $list[0];
		}
	}

	/**
	 * @return boolean
	 */
	public function isActive()
	{
		return $this->getActive() == 'Y' ? true : false;
	}

	/**
	 */
	public function toggleActive()
	{
		$this->active = $this->isActive()? 'N' : 'Y';
	}

	/**
	 * Returns the StatusChange that was active on the given date
	 *
	 * Defaults to the current date
	 *
	 * @param Date $date
	 */
	public function getStatus(Date $date=null)
	{
		$targetDate = $date ? $date : new Date();
		$list = new LocationStatusChangeList();
		$list->find(array('location_id'=>$this->location_id,'current'=>$targetDate));
		if (count($list)) {
			return $list[0];
		}
	}

	/**
	 * Saves a new LocationStatusChange to the database
	 *
	 * @param AddressStatus|string $status
	 */
	 public function saveStatus($status)
	 {
		if (!$status instanceof AddressStatus) {
			$status = new AddressStatus($status);
		}
		$currentStatus = $this->getStatus();
		if (!$currentStatus) {
			$newStatus = new LocationStatusChange();
			$newStatus->setLocation_id($this->getLocation_id());
			$newStatus->setStatus($status);
			$newStatus->save();
		}
		if ($currentStatus
			&& $currentStatus->getStatus_code() != $status->getStatus_code()) {
			$currentStatus->setEnd_date(time());
			$currentStatus->save();
		}
	 }

	/**
	 * @param string string (y or n)
	 */
	public function setMailable($string)
	{
		$this->mailable_flag = $string == 'y' ? 1 : 0;
	}

	/**
	 * @param string string
	 */
	public function setLivable($string)
	{
		$this->livable_flag = $string == 'y' ? 1 : 0;
	}

	/**
	 * @return boolean
	 */
	public function isMailable()
	{
		return $this->getMailable_flag() ? true : false;
	}

	/**
	 * @return boolean
	 */
	public function isLivable()
	{
		return $this->getLivable_flag() ? true : false;
	}

	/**
	 * Alias for getLocationType()
	 *
	 * @return LocationType
	 */
	public function getType()
	{
		return $this->getLocationType();
	}
}
