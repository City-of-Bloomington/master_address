<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
class Location
{
	private $location_id;
	private static $flagValues = array('yes','no','unknown');

	public static function getFlagValues()
	{
		return self::$flagValues;
	}

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
	 * @param int $location_id
	 */
	public function __construct($location_id=null)
	{
		if ($location_id) {
			$this->location_id = $location_id;
		}
		else {
			// This is where the code goes to generate a new, empty instance.
			// Set any default values for properties that need it here

		}
	}

	/**
	 * @return int
	 */
	public function getLocation_id()
	{
		return $this->location_id;
	}

	/**
	 * Alias for getLocation_id()
	 *
	 * @return int
	 */
	public function getId()
	{
		return $this->getLocation_id();
	}

	/**
	 * Looks up the addresses for this location
	 *
	 * @param array $fields
	 * @return AddressList
	 */
	public function getAddresses(array $fields=null)
	{
		if ($this->location_id) {
			$search = array('location_id'=>$this->location_id);
			if ($fields) {
				$search = array_merge($search,$fields);
			}
			return new AddressList($search);
		}
	}

	/**
	 * Looks up the addresses for this location
	 *
	 * @param array $fields
	 * @return AddressList
	 */
	public function getSubunits(array $fields=null)
	{
		if ($this->location_id) {
			$search = array('location_id'=>$this->location_id);
			if ($fields) {
				$search = array_merge($search,$fields);
			}
			return new SubunitList($search);
		}
	}

	/**
	 * Assigns an address.  Creates a new Location_ID if we don't have one yet
	 *
	 * @param Address|Subunit $address
	 * @param LocationType $type
	 */
	public function assign($address,LocationType $type)
	{
		if ($address instanceof Address) {
			$data['street_address_id'] = $address->getId();
		}
		elseif ($address instanceof Subunit) {
			$data['street_address_id'] = $address->getStreet_address_id();
			$data['subunit_id'] = $address->getId();
		}
		else {
			throw new Exception('locations/invalidAddress');
		}

		$zend_db = Database::getConnection();

		if (!$this->location_id) {
			$this->location_id = $zend_db->nextSequenceId('location_id_s');
		}

		// If it's not in the database already, add a new row
		$sql = 'select count(*) from address_location where location_id=? and street_address_id=?';
		$parameters = array($this->location_id,$data['street_address_id']);
		if (isset($data['subunit_id'])) {
			$sql.= ' and subunit_id=?';
			$parameters[] = $data['street_address_id'];
		}

		$count = $zend_db->fetchOne($sql,$parameters);
		if (!$count) {
			$data['location_id'] = $this->location_id;
			$data['location_type_id'] = $type->getId();
			$data['active'] = 'N';
			$zend_db->insert('address_location',$data);
		}
	}

	/**
	 * Moves an address to a different location
	 *
	 * This is usually done in cleanup of bad records.
	 * Moving an address to a different location will cause
	 * this location to be purged from the database.
	 *
	 * @param Address $address
	 * @param Location $newLocation
	 */
	public function moveAddress(Address $address,Location $newLocation)
	{
		if ($this->location_id
			&& $newLocation->getId() != $this->location_id) {
			$zend_db = Database::getConnection();
			$zend_db->update('address_location',
							array('location_id'=>$newLocation->getId(),'active'=>'N'),
							"location_id={$this->getId()} and street_address_id={$address->getId()}");

			// If there are no more addresses at this location, purge this location
			$count = $zend_db->fetchOne('select count(*) from address_location where location_id=?',
										$this->getId());
			if (!$count) {
				$zend_db->delete('mast_address_location_change',"location_id={$this->getId()}");
				$zend_db->delete('mast_address_location_status',"location_id={$this->getId()}");
				$zend_db->delete('mast_address_assignment_hist',"location_id={$this->getId()}");
				$zend_db->delete('building_address_location',"location_id={$this->getId()}");
			}
		}
	}

	/**
	 * Sets the given address or subunit as active
	 * Deactivates all the other addresses or subunits
	 *
	 * @param Address|Subunit $address
	 */
	public function activate($address)
	{
		if ($this->location_id) {
			if ($address instanceof Address) {
				$field = 'street_address_id';
				$where = "$field!={$address->getId()}";
			}
			elseif ($address instanceof Subunit) {
				$field = 'subunit_id';
				$where = "$field is not null and $field!={$address->getId()}";
			}
			else {
				throw new Exception('locations/invalidAddress');
			}

			$zend_db = Database::getConnection();
			$zend_db->update('address_location',
							array('active'=>'N'),
							"location_id={$this->location_id} and $where");
			$zend_db->update('address_location',
							array('active'=>'Y'),
							"location_id={$this->location_id} and $field={$address->getId()}");
		}
	}

	/**
	 * Allows you to set values for mailable,livable,locationType,common_name
	 * @param array $post
	 * @param Address|Subunit $address
	 */
	public function update(array $post,$address)
	{
		if ($this->location_id) {
			if ($address instanceof Address || $address instanceof Subunit) {
				$data = array();
				if (isset($post['mailable'])) {
					$data['mailable_flag'] = 'unknown';
					if (in_array($post['mailable'],self::$flagValues)) {
						$data['mailable_flag'] = $post['mailable'];
					}
				}
				if (isset($post['livable'])) {
					$data['livable_flag'] = 'unknown';
					if (in_array($post['livable'],self::$flagValues)) {
						$data['livable_flag'] = $post['livable'];
					}
				}
				if (isset($post['common_name'])) {
					$data['common_name'] = $post['common_name'];
				}
				if (isset($post['locationType'])) {
					$locationType = $post['locationType'] instanceof LocationType
									? $post['locationType']
									: new LocationType($post['locationType']);
					$data['location_type_id'] = $locationType->getId();
				}

				$field = $address instanceof Address ? 'street_address_id' : 'subunit_id';
				$where = "location_id={$this->location_id} and $field={$address->getId()}";

				$zend_db = Database::getConnection();
				$zend_db->update('address_location',$data,$where);

			}
			else {
				throw new Exception('locations/invalidAddress');
			}
		}
		else {
			throw new Exception('locations/unknownLocation');
		}
	}

	/**
	 * Returns an array of current data that can be set using update()
	 *
	 * @param Address $address
	 * @return array
	 */
	public function getUpdatableData($address)
	{
		$data = array();
		$data['mailable'] = $this->getMailable($address);
		$data['livable'] = $this->getLivable($address);
		$data['locationType'] = $this->getLocationType($address);
		$data['common_name'] = $this->getCommonName($address);
		return $data;
	}

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
		if ($this->location_id) {
			$list = new PurposeList(array('location_id'=>$this->location_id,
											'type'=>'CITY COUNCIL DISTRICT'));
			if (count($list)) {
				return $list[0];
			}
		}
	}

	/**
	 * Returns the AddressStatus that was active on the given date
	 *
	 * Defaults to the latest statusChange, which might not be current according
	 * to the dates.
	 *
	 * @param Date $date
	 * @return AddressStatus
	 */
	public function getStatus(Date $date=null)
	{
		if ($this->location_id) {
			$search = array('location_id'=>$this->location_id);
			if ($date) {
				$search['current'] = $date;
			}
			$list = new LocationStatusChangeList($search);
			if (count($list)) {
				$statusChange = $list[0];
				return $statusChange->getStatus();
			}
		}
	}

	/**
	 * Saves a new LocationStatusChange to the database
	 *
	 * As we update the status history table, we need to clean up old data
	 * If there is no current status, we just save the new status.
	 * If there is a current status AND it's the same as the new status - then we don't do anything
	 *
	 * Data Cleanup: If there is a current status, and it's not the same as the
	 * new status, we need to set end dates on ALL the old statuses that need them.
	 * There maybe be multiple status changes in the database, that have not had
	 * their end dates set.  They didn't use to do it that way, but now they do.
	 *
	 * @param AddressStatus|string $status
	 */
	public function saveStatus($status)
	{
		if ($this->location_id) {
			if (!$status instanceof AddressStatus) {
				$status = new AddressStatus($status);
			}
			$currentStatus = $this->getStatus();
			// If we don't have a current status, or it's different than the new one.
			// We create the new status change object.  We'll save it later
			if (!$currentStatus ||
				($currentStatus->getStatus_code() != $status->getStatus_code())) {
				$newStatus = new LocationStatusChange();
				$newStatus->setLocation($this);
				$newStatus->setStatus($status);
			}

			// If we have a current status, and it's not the same as the new one,
			// Do our data cleanup - use today's date on all the empty end dates
			if ($currentStatus
				&& $currentStatus->getStatus_code() != $status->getStatus_code()) {
				$zend_db = Database::getConnection();
				$zend_db->update('mast_address_location_status',
								array('effective_end_date'=>date('Y-m-d H:i:s')),
									"location_id='{$this->location_id}' and effective_end_date is null");
			}

			// If we have a new status, go ahead and save it.
			// The data should be nice and clean now
			if (isset($newStatus)) {
				$newStatus->save();
			}
		}
	}

	/**
	 * Queries the database for a single location property
	 *
	 * @param string $fieldname
	 * @param Address|Subunit $address
	 */
	private function fieldLookup($fieldname,$address)
	{
		if ($this->location_id) {
			if ($address instanceof Address) {
				$lookup = 'street_address_id';
			}
			elseif ($address instanceof Subunit) {
				$lookup = 'subunit_id';
			}
			else {
				throw new Exception('locations/invalidAddress');
			}

			$zend_db = Database::getConnection();
			$sql = "select $fieldname from address_location where location_id=? and $lookup=?";
			return $zend_db->fetchOne($sql,array($this->location_id,$address->getId()));
		}
	}

	/**
	 * @param Address|Subunit $address
	 * @return string
	 */
	public function getMailable($address)
	{
		return $this->fieldLookup('mailable_flag',$address);
	}

	/**
	 * @param Address|Subunit $address
	 * @return string
	 */
	public function getLivable($address)
	{
		return $this->fieldLookup('livable_flag',$address);
	}

	/**
	 * @param Address|Subunit $address
	 * @return boolean
	 */
	public function isActive($address)
	{
		return $this->fieldLookup('active',$address)=='Y' ? true : false;
	}

	/**
	 * Tells whether there's at least one active address or subunit for this location
	 *
	 * @param Address|Subunit $address
	 * @return boolean
	 */
	public function hasActive($address)
	{
		if ($this->location_id) {
			$sql = 'select count(*) from address_location where active=? and location_id=?';

			if ($address instanceof Address) {
				$sql.= ' and street_address_id=?';
			}
			elseif ($address instanceof Subunit) {
				$sql.= ' and subunit_id=?';
			}
			else {
				// Don't bother throwing an exception
				return false;
			}

			$zend_db = Database::getConnection();
			$count = $zend_db->fetchOne($sql,array('Y',$this->location_id,$address->getId()));
			return $count ? true : false;
		}
	}

	/**
	 * @param Address|Subunit $address
	 * @return LocationType
	 */
	public function getLocationType($address)
	{
		$type_id = $this->fieldLookup('location_type_id',$address);
		if ($type_id) {
			return new LocationType($type_id);
		}
	}

	/**
	 * @param Address|Subunit $address
	 * @return string
	 */
	public function getCommonName($address)
	{
		return $this->fieldLookup('common_name',$address);
	}
}
