<?php
/**
 * @copyright 2009-2014 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
class Address
{
	private $street_address_id;
	private $street_number_prefix;
	private $street_number;
	private $street_number_suffix;
	private $street_id;
	private $address_type;
	private $addr_jurisdiction_id;	// addr_jurisdiction_id is unused
	private $gov_jur_id;		// This is the real jurisdiction
	private $township_id;
	private $section;
	private $quarter_section;
	private $subdivision_id;
	private $plat_id;
	private $plat_lot_number;
	private $street_address_2;
	private $city;
	private $state;
	private $zip;
	private $zipplus4;
	private $state_plane_x_coordinate;
	private $state_plane_y_coordinate;
	private $latitude;
	private $longitude;
	private $usng_coordinate;
	private $notes;

	private $status_code;	// Used for pre-loading the latest status
	private $description; 	// Used for pre-loading the latest status
	private $status;	// Stores the latest AddressStatus object

	private $trash_pickup_day;	// Comes from mast_address_sanitation
	private $recycle_week;		// Comes from mast_address_sanitation

	private $street;
	private $jurisdiction;
	private $township;
	private $subdivision;
	private $plat;

	private $location;

	private static $addressTypes = array("Street","Utility","Property",
										"Parcel","Facility","Temporary");

	private static $zipcodes = array();

	/**
	 * @return array
	 */
    public static function getZipCodes()
    {
		if (!self::$zipcodes) {
			$zend_db = Database::getConnection();
			$sql = 'select * from zipcodes order by zip';
			$result = $zend_db->fetchAll($sql);
			foreach ($result as $row) {
				self::$zipcodes[$row['zip']] = $row['city'];
			}
		}
		return self::$zipcodes;
    }

	/**
	 * @return array
	 */
	public static function getSections()
	{
		$zend_db = Database::getConnection();
		$sql = "select distinct section from mast_address
				where section is not null
				order by section";
		return $zend_db->fetchCol($sql);
	}

	/**
	 * @return array
	 */
	public static function getQuarterSections()
	{
		return array('NE','NW','SE','SW');
	}

	/**
	 * Returns the method names of actions that are supported
	 *
	 * These actions all share the same interface
	 * $this->action($post,$changeLogEntry);
	 *
	 * @return array
	 */
	public static function getActions()
	{
		return array('correct','update','readdress','unretire','reassign','retire','verify');
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
	 * @param int|array $street_address_id
	 */
	public function __construct($street_address_id=null)
	{
		if ($street_address_id) {
			if (is_array($street_address_id)) {
				$result = $street_address_id;
			}
			else {
				$zend_db = Database::getConnection();
				$sql = "select a.*, s.trash_pickup_day, s.recycle_week, l.status_code, l.description
						from mast_address a
						left join mast_address_sanitation s on a.street_address_id=s.street_address_id
						left join mast_address_latest_status l on a.street_address_id=l.street_address_id
						where a.street_address_id=?";
				$result = $zend_db->fetchRow($sql,array($street_address_id));
			}

			if ($result) {
				foreach ($result as $field=>$value) {
					if ($value) {
						$this->$field = $value;
					}
				}
				$this->status = new AddressStatus(array('status_code'=>$this->status_code,
														'description'=>$this->description));
			}
			else {
				throw new Exception('addresses/unknownAddress');
			}
		}
		else {
			// This is where the code goes to generate a new, empty instance.
			// Set any default values for properties that need it here
			$this->city="Bloomington";
			$this->state="IN";
            $this->gov_jur_id=1;// bloomington
		}
	}

	public function __clone()
	{
		$this->street_address_id = null;
		$this->status = null;
	}

	/**
	 * Throws an exception if anything's wrong
	 * @throws Exception $e
	 */
	public function validate()
	{
		// Check for required fields here.  Throw an exception if anything is missing.
		if (!$this->street_id || !$this->street_number || !$this->zip || !$this->section
			|| !$this->address_type || !$this->gov_jur_id || !$this->township_id) {
			throw new Exception('missingRequiredFields');
		}

		// Make sure this is not a duplicate address
		$zend_db = Database::getConnection();
		$sql = "select count(*) from mast_address
				where street_id=?
				  and street_number_prefix=? and street_number=? and street_number_suffix=?";
		$count = $zend_db->fetchOne($sql, [
			$this->street_id,
			$this->street_number_prefix, $this->street_number, $this->street_number_suffix
		]);
		if ((!$this->street_address_id && $count) || $count>1) {
			throw new Exception('addresses/duplicateAddress');
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
		$data['a']['street_number_prefix'] = $this->street_number_prefix ? $this->street_number_prefix : null;
		$data['a']['street_number'] = $this->street_number;
		$data['a']['street_number_suffix'] = $this->street_number_suffix ? $this->street_number_suffix : null;
		$data['a']['street_id'] = $this->street_id;
		$data['a']['address_type'] = $this->address_type;
		$data['a']['gov_jur_id'] = $this->gov_jur_id;
		$data['a']['township_id'] = $this->township_id;
		$data['a']['section'] = $this->section;
		$data['a']['quarter_section'] = $this->quarter_section ? $this->quarter_section : null;
		$data['a']['subdivision_id'] = $this->subdivision_id ? $this->subdivision_id : null;
		$data['a']['plat_id'] = $this->plat_id ? $this->plat_id : null;
		$data['a']['plat_lot_number'] = $this->plat_lot_number ? $this->plat_lot_number : null;
		$data['a']['street_address_2'] = $this->street_address_2 ? $this->street_address_2 : null;
		$data['a']['city'] = $this->city ? $this->city : null;
		$data['a']['state'] = $this->state ? $this->state : null;
		$data['a']['zip'] = $this->zip;
		$data['a']['zipplus4'] = $this->zipplus4 ? $this->zipplus4 : null;
		$data['a']['notes'] = $this->notes ? $this->notes : null;

		if ($this->street_address_id) {
			$this->updateDB($data);
		}
		else {
			$this->insertDB($data);
		}

		$this->updateChangeLog($changeLogEntry);
	}

	private function updateDB($data)
	{
		$zend_db = Database::getConnection();
		$zend_db->update('mast_address',$data['a'],"street_address_id='{$this->street_address_id}'");
	}

	private function insertDB($data)
	{
		$zend_db = Database::getConnection();
		$zend_db->insert('mast_address',$data['a']);
		if (Database::getType()=='oracle') {
			$this->street_address_id = $zend_db->lastSequenceId('street_address_id_s');
		}
		else{
		     $this->street_address_id = $zend_db->lastInsertId('mast_address','street_address_id');
		}
	}

	//----------------------------------------------------------------
	// Generic Getters
	//----------------------------------------------------------------
	/**
	 * Alias for getStreet_address_id()
	 *
	 * @return int
	 */
	public function getId()
	{
		return $this->getStreet_address_id();
	}

	/**
	 * @return number
	 */
	public function getStreet_address_id()
	{
		return $this->street_address_id;
	}

	/**
	 * @return string
	 */
	public function getStreet_number_prefix()
	{
		return $this->street_number_prefix;
	}

	/**
	 * @return string
	 */
	public function getStreet_number()
	{
		return $this->street_number;
	}

	/**
	 * @return string
	 */
	public function getStreet_number_suffix()
	{
		return $this->street_number_suffix;
	}

	/**
	 * @return number
	 */
	public function getStreet_id()
	{
		return $this->street_id;
	}

	/**
	 * @return string
	 */
	public function getAddress_type()
	{
		return $this->address_type;
	}

	/**
	 * Alias for getGov_jur_id()
	 *
	 * The real jurisdictions are the Governmental Jurisdictions
	 * @return int
	 */
	public function getJurisdiction_id()
	{
		return $this->getGov_jur_id();
	}

	/**
	 * @return number
	 */
	public function getGov_jur_id()
	{
		return $this->gov_jur_id;
	}

	/**
	 * @return number
	 */
	public function getTownship_id()
	{
		return $this->township_id;
	}

	/**
	 * @return string
	 */
	public function getSection()
	{
		return $this->section;
	}

	/**
	 * @return char
	 */
	public function getQuarter_section()
	{
		return $this->quarter_section;
	}

	/**
	 * @return number
	 */
	public function getSubdivision_id()
	{
		return $this->subdivision_id;
	}

	/**
	 * @return number
	 */
	public function getPlat_id()
	{
		return $this->plat_id;
	}

	/**
	 * @return number
	 */
	public function getPlat_lot_number()
	{
		return $this->plat_lot_number;
	}

	/**
	 * @return string
	 */
	public function getStreet_address_2()
	{
		return $this->street_address_2;
	}

	/**
	 * @return string
	 */
	public function getCity()
	{
		return $this->city;
	}

	/**
	 * @return string
	 */
	public function getState()
	{
		return $this->state;
	}

	/**
	 * @return string
	 */
	public function getZip()
	{
		return $this->zip;
	}

	/**
	 * @return string
	 */
	public function getZipplus4()
	{
		return $this->zipplus4;
	}

	/**
	 * @return string
	 */
	public function getZips()
	{
		$ret = $this->zip;
		if($this->zipplus4){
		    $ret .=" - ".$this->zipplus4;
		}
		return $ret;
	}
	/**
	 * @return number
	 */
	public function getState_plane_x_coordinate()
	{
		return $this->state_plane_x_coordinate;
	}

	/**
	 * @return number
	 */
	public function getState_plane_y_coordinate()
	{
		return $this->state_plane_y_coordinate;
	}

	/**
	 * @return string of pair of numbers
	 */
	public function getState_plane_xy_coordinate()
	{
		$ret =  $this->state_plane_x_coordinate;
		if($this->state_plane_y_coordinate){
		    if($ret) $ret .=', ';
		    $ret .= $this->state_plane_y_coordinate;
		}
		return $ret;
	}

	/**
	 * @return number
	 */
	public function getLatitude()
	{
		return $this->latitude;
	}

	/**
	 * @return number
	 */
	public function getLongitude()
	{
		return $this->longitude;
	}

	/**
	 * @return number
	 */
	public function getLatLong()
	{
	    $ret = $this->latitude;
	    if($this->longitude){
		    $ret .=', ';
		    $ret .= $this->longitude;
	    }
	    return $ret;
	}

	/**
	 * @return string
	 */
	public function getUsng_coordinate()
	{
		return $this->usng_coordinate;
	}

	/**
	 * @return string
	 */
	public function getNotes()
	{
		return $this->notes;
	}

	/**
	 * @return string
	 */
	public function getTrash_pickup_day()
	{
		return $this->trash_pickup_day;
	}

	/**
	 * @return string
	 */
	public function getRecycle_week()
	{
		return $this->recycle_week;
	}

	/**
	 * @return Street
	 */
	public function getStreet()
	{
		if (!$this->street && $this->street_id) {
			$this->street = new Street($this->street_id);
		}
		return $this->street;
	}

	/**
	 * Returns the status for this Address on a give date
	 *
	 * @param Date $date
	 * @return AddressStatus
	 */
	public function getStatus(Date $date=null)
	{
		if (!$date) {
			return $this->status;
		}
		else {
			$statusHistory = $this->getStatusChangeList(array('current'=>$date));
			if (count($statusHistory)) {
				return $list[0];
			}
		}
	}

	/**
	 * Returns the status history for this address
	 *
	 * @return AddressStatusChangeList
	 */
	public function getStatusChangeList(array $fields=null)
	{
		$search = array('street_address_id'=>$this->street_address_id);
		if ($fields) {
			$search = array_merge($search,$fields);
		}
		return new AddressStatusChangeList($search);
	}

	/**
	 * The real jurisdictions are the Governmental Jurisdictions
	 * @return Jurisdiction
	 */
	public function getJurisdiction()
	{
		if (!$this->jurisdiction) {
			$this->jurisdiction = new Jurisdiction($this->gov_jur_id);
		}
		return $this->jurisdiction;
	}

	/**
	 * @return Township
	 */
	public function getTownship()
	{
		if ($this->township_id) {
			if (!$this->township) {
				$this->township = new Township($this->township_id);
			}
			return $this->township;
		}
		return null;
	}

	/**
	 * @return Subdivision
	 */
	public function getSubdivision()
	{
		if ($this->subdivision_id) {
			if (!$this->subdivision) {
				$this->subdivision = new Subdivision($this->subdivision_id);
			}
			return $this->subdivision;
		}
		return null;
	}

	/**
	 * @return Plat
	 */
	public function getPlat()
	{
		if ($this->plat_id) {
			if (!$this->plat) {
				$this->plat = new Plat($this->plat_id);
			}
			return $this->plat;
		}
		return null;
	}

	/**
	 * @return SubunitList
	 */
	public function getSubunits()
	{
		return new SubunitList(array('street_address_id'=>$this->street_address_id));
	}

	/**
	 * @return int
	 */
	public function getSubunitCount()
	{
		$zend_db = Database::getConnection();
		$sql = 'select count(*) from mast_address_subunits where street_address_id=?';
		$count = $zend_db->fetchOne($sql,$this->street_address_id);
		return $count;
	}

	//----------------------------------------------------------------
	// Generic Setters
	//----------------------------------------------------------------
	public function setStreet_number_prefix($string)
	{
		$this->street_number_prefix = strtoupper(trim($string));
	}
	/**
	 * @param string $string
	 */
	public function setStreet_number($int)
	{
		$this->street_number = (int)$int;
	}

	public function setStreet_number_suffix($string)
	{
		$this->street_number_suffix = strtoupper(trim($string));
	}

	/**
	 * @param int $int
	 */
	public function setStreet_id($int=null)
	{
		if ($int) {
			$this->street = new Street($int);
			$this->street_id = $this->street->getId();
		}
		else {
			$this->street = null;
			$this->street_id = null;
		}
	}

	/**
	 * @param string $string
	 */
	public function setAddress_type($string)
	{
		$this->address_type = trim($string);
	}

	/**
	 * The real jurisdictions are the Governmental Jurisdictions
	 *
	 * @param int $number
	 */
	public function setJurisdiction_id($number)
	{
		$this->setGov_jur_id($number);
	}

	/**
	 * @param int $number
	 */
	public function setGov_jur_id($number)
	{
		$this->gov_jur_id = (int)$number;
	}

	/**
	 * @param int $int
	 */
	public function setTownship_id($int)
	{
		$this->township = new Township($int);
		$this->township_id = $this->township->getId();
	}

	/**
	 * @param int $int
	 */
	public function setSection($int)
	{
		$this->section = preg_replace('/[^0-9]/','',$int);
	}

	/**
	 * @param string $string
	 */
	public function setQuarter_section($string)
	{
		$this->quarter_section = preg_replace('/[^NSEW]/','',strtoupper($string));
	}

	/**
	 * @param int $number
	 */
	public function setSubdivision_id($number)
	{
		$this->subdivision_id = (int)$number;
	}

	/**
	 * @param int $number
	 */
	public function setPlat_id($number)
	{
		$this->plat_id = (int)$number;
	}

	/**
	 * @param string $string
	 */
	public function setPlat_lot_number($string)
	{
		$this->plat_lot_number = $string;
	}

	/**
	 * @param string $string
	 */
	public function setStreet_address_2($string)
	{
		$this->street_address_2 = trim($string);
	}

	/**
	 * @param string $string
	 */
	public function setCity($string)
	{
		$this->city = trim($string);
	}

	/**
	 * @param string $string
	 */
	public function setState($string)
	{
		$this->state = trim($string);
	}

	/**
	 * @param int $int
	 */
	public function setZip($int)
	{
		$zipcodes = $this->getZipCodes();
		if (in_array($int,array_keys($zipcodes))) {
			$this->zip = (int)$int;
			$this->setCity($zipcodes[$int]);
		}
	}

	/**
	 * @param string $string
	 */
	public function setZipplus4($string)
	{
		$this->zipplus4 = preg_replace('/[^0-9]/','',$string);
	}

	/**
	 * @param string $string
	 */
	public function setNotes($string)
	{
		$this->notes = trim($string);
	}

	/**
	 * @param Street $street
	 */
	public function setStreet(Street $street)
	{
		$this->street_id = $street->getId();
		$this->street = $street;
	}

	/**
	 * @param Jurisdiction $jurisdiction
	 */
	public function setJurisdiction(Jurisdiction $jurisdiction)
	{
		$this->gov_jur_id = $jurisdiction->getId();
		$this->jurisdiction = $jurisdiction;
	}

	/**
	 * @param Township $township
	 */
	public function setTownship(Township $township)
	{
		$this->township_id = $township->getId();
		$this->township = $township;
	}

	/**
	 * @param Subdivision $subdivision
	 */
	public function setSubdivision(Subdivision $subdivision)
	{
		$this->subdivision_id = $subdivision->getId();
		$this->subdivision = $subdivision;
	}

	/**
	 * @param Plat $plat
	 */
	public function setPlat(Plat $plat)
	{
		$this->plat_id = $plat->getId();
		$this->plat = $plat;
	}


	//----------------------------------------------------------------
	// Custom Functions
	// We recommend adding all your custom code down here at the bottom
	//----------------------------------------------------------------
	public function __toString()
	{
		return $this->getStreetAddress();
	}

	/**
	 * Alias for getAddress_type()
	 *
	 * @return string
	 */
	public function getType()
	{
		return $this->getAddress_type();
	}

	/**
	 * @return array
	 */
	public static function getAddressTypes()
	{
		return self::$addressTypes;
	}

	/**
	 * Returns the complete street number
	 *
	 * The complete street number includes prefix, number, and suffix
	 *
	 * @return string
	 */
	public function getStreetNumber()
	{
        $number = '';
        if ($this->street_number_prefix) { $number = "{$this->street_number_prefix} "; }
        $number.= $this->street_number;
        if ($this->street_number_suffix) { $number.= " {$this->street_number_suffix}"; }
        return $number;
	}

	/**
	 * @return string
	 */
	public function getStreetAddress()
	{
		return "{$this->getStreetNumber()} {$this->getStreet()->getStreetName()}";
	}

	/**
	 * @return StreetName
	 */
	public function getStreetName()
	{
		return $this->getStreet()->getStreetName();
	}

	/**
	 * @return LocationList
	 */
	public function getLocations(array $fields=null)
	{
		$search = array('street_address_id'=>$this->street_address_id,
						'subunit_id'=>null);
		if ($fields) {
			$search = array_merge($search,$fields);
		}
		return new LocationList($search);
	}

	/**
	 * @return Location
	 */
	public function getLocation()
	{
		if (!$this->location) {
			// See if this address is the active location
			$list = new LocationList(array('street_address_id'=>$this->street_address_id,
											'subunit_id'=>null,
											'active'=>'Y'));
			if (count($list)) {
				$this->location = $list[0];
			}
			// This address is not the active address for any location.
			else {
				// See if this address has any locations at all.
				$list = new LocationList(array('street_address_id'=>$this->street_address_id,
												'subunit_id'=>null));
				if (count($list)) {
					$this->location = $list[0];
				}
			}
		}
		return $this->location;
	}

	/**
	 * Returns the Mailable information from this address's primary location
	 *
	 * @return string
	 */
	public function getMailable()
	{
		if ($this->getLocation()) {
			return $this->getLocation()->getMailable($this);
		}
	}

	/**
	 * Returns the Livable information from this address's primary location
	 *
	 * @return string
	 */
	public function getLivable()
	{
		if ($this->getLocation()) {
			return $this->getLocation()->getLivable($this);
		}
	}

	/**
	 * Returns the Active information from this address's primary location
	 *
	 * @return boolean
	 */
	public function isActive()
	{
		return ($this->getLocation() && $this->getLocation()->isActive($this));
	}

	/**
	 * @return PurposeList
	 */
	public function getPurposes()
	{
		return new PurposeList(array('street_address_id'=>$this->street_address_id));
	}

	/**
	 * @return string
	 */
	public function getURL()
	{
		return BASE_URL.'/addresses/viewAddress.php?address_id='.$this->street_address_id;
	}

	/**
	 * Returns the name of the city council district
	 *
	 * @return string
	 */
	public function getCityCouncilDistrict()
	{
		$purpose = $this->getLocation()->getCityCouncilPurpose();
		return $purpose ? $purpose->getDescription() : '';
	}

	/**
	 * Returns the days the city does trash pickup
	 *
	 * @return array
	 */
	public static function getTrashDays()
	{
		return array('MONDAY','TUESDAY','WEDNESDAY','THURSDAY','FRIDAY');
	}

	/**
	 * Returns the letter code for the weeks that the city picks up recycling
	 *
	 * @return array
	 */
	public static function getRecycleWeeks()
	{
		return array('A','B');
	}

	/**
	 * Alias for getTrash_pickup_day()
	 * @return string
	 */
	public function getTrashDay()
	{
		return $this->getTrash_pickup_day();
	}

	/**
	 * Alias for getRecycle_week()
	 * @return string
	 */
	public function getRecycleWeek()
	{
		return $this->getRecycle_week();
	}

	/**
	 * Saves a ChangeLogEntry to the database for this this address
	 *
	 * @param ChangeLogEntry $changeLogEntry
	 */
	public function updateChangeLog(ChangeLogEntry $changeLogEntry)
	{
		$logEntry = $changeLogEntry->getData();
		$logEntry['street_address_id'] = $this->street_address_id;

		$zend_db = Database::getConnection();
		$zend_db->insert('address_change_log',$logEntry);
	}

	/**
	 * Returns an array of ChangeLogEntries
	 *
	 * @return array
	 */
	public function getChangeLog()
	{
		$changeLog = array();
		if ($this->street_address_id) {
			$zend_db = Database::getConnection();
			$sql = 'select * from address_change_log where street_address_id=? order by action_date';
			$result = $zend_db->fetchAll($sql,$this->street_address_id);
			foreach ($result as $row) {
				$changeLog[] = new ChangeLogEntry($row);
			}
		}
		return $changeLog;
	}

	/**
	 * Returns a Zend_Paginator for the raw database results
	 *
	 * If you ask for this, you must remember to create a ChangeLogEntry out of
	 * each row of the results.
	 * $changeLogEntry = new ChangeLogEntry($row)
	 *
	 * @return Zend_Paginator
	 */
	public function getChangeLogPaginator()
	{
		if ($this->street_address_id) {
			$zend_db = Database::getConnection();
			$select = $zend_db->select()->from('address_change_log');
			$select->where('street_address_id=?',$this->street_address_id);
			$select->order('action_date desc');

			return new Zend_Paginator(new Zend_Paginator_Adapter_DbSelect($select));
		}
	}

	/**
	 * Saves a new AddressStatusChange to the database
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
		if (!$status instanceof AddressStatus) {
			$status = new AddressStatus($status);
		}
		$currentStatus = $this->getStatus();
		// If we don't have a current status, or it's different than the new one.
		// We create the new status change object.  We'll save it later
		if (!$currentStatus ||
			($currentStatus->getStatus_code() != $status->getStatus_code())) {
			$newStatus = new AddressStatusChange();
			$newStatus->setAddress($this);
			$newStatus->setStatus($status);
		}

		// If we have a current status, and it's not the same as the new one,
		// Do our data cleanup - use today's date on all the empty end dates
		if ($currentStatus
			&& $currentStatus->getStatus_code() != $status->getStatus_code()) {
			$zend_db = Database::getConnection();
			$zend_db->update('mast_address_status',
								array('end_date'=>date('Y-m-d H:i:s')),
								"street_address_id='{$this->street_address_id}'  and end_date is null");
		}

		// If we have a new status, go ahead and save it.
		// The data should be nice and clean now
		if (isset($newStatus)) {
			$newStatus->save();
			$this->status = $status;
			$this->status_code = $status->getCode();
			$this->description = $status->getDescription();
		}
	}

	/**
	 * Update an error in the Address
	 *
	 * Updates are for people to fix invalid data and other typos for the address.
	 * @param array $post
	 * @param ChangeLogEntry $changeLogEntry
	 */
	public function update($post,ChangeLogEntry $changeLogEntry)
	{
		// These are the fields that are allowed to be set during a correction
		$fields = array('address_type','plat_id','plat_lot_number',
						'jurisdiction_id',
						'township_id','section','quarter_section','notes');
		foreach ($fields as $field) {
			if (isset($post[$field])) {
				$set = 'set'.ucfirst($field);
				$this->$set($post[$field]);
			}
		}

		// Update the mailable, livable flags on all the locations for this address
		foreach ($this->getLocations() as $location) {
			$data['mailable'] = $post['mailable'];
			$data['livable'] = $post['livable'];
			$data['locationType'] = $post['location_type_id'];
			$location->update($data,$this);
		}

		$this->save($changeLogEntry);
	}

	/**
	 * Correct an error in the primary attributes of this address
	 *
	 * Changes to these fields of the address require special reporting and
	 * have to be handled seperately.
	 *
	 * @param array $post
	 * @param ChangeLogEntry $changeLogEntry
	 */
	public function correct($post,ChangeLogEntry $changeLogEntry)
	{
		$fields = [
			'street_id', 'street_number_prefix', 'street_number','street_number_suffix',
			'zip','zipplus4','notes'
		];

		foreach ($fields as $field) {
			if (isset($post[$field])) {
				$set = 'set'.ucfirst($field);
				$this->$set($post[$field]);
			}
		}
		$this->save($changeLogEntry);
	}

	/**
	 * Process a change of address
	 *
	 * For a change of address, we need to preserve the old address.
	 * We retire the old address, and create a new address at the same location
	 * The new address will probably have a different street and street number.
	 *
	 * @param array $post
	 * @param ChangeLogEntry $changeLogEntry
	 */
	public function readdress($post,ChangeLogEntry $changeLogEntry)
	{
		$fields = ['street_id', 'street_number_prefix', 'street_number', 'street_number_suffix', 'notes'];

		// Create the new address
		$newAddress = clone($this);
		foreach ($fields as $f) {
			$set = 'set'.ucfirst($f);
			$newAddress->$set($post[$f]);
		}
		$newAddress->save($changeLogEntry);
		$newAddress->saveStatus('CURRENT');

		// Assign the new address to the same location
		$location = $this->getLocation();
		$location->assign($newAddress,$location->getLocationType($this));
		$location->activate($newAddress);
		$location->saveStatus('CURRENT');

		// Move all the subunits over to the new address
		foreach ($this->getSubunits() as $subunit) {
			$subunit->moveToAddress($newAddress,$changeLogEntry);
		}

		$this->retire($post,$changeLogEntry);
	}

	/**
	 * Sets the latest status for this address to CURRENT
	 *
	 * @param array $post
	 * @param ChangeLogEntry $changeLogEntry
	 */
	public function unretire($post,ChangeLogEntry $changeLogEntry)
	{
		$this->saveStatus('CURRENT');
		$this->getLocation()->saveStatus('CURRENT');
		if (!$this->getLocation()->hasActive($this)) {
			$this->getLocation()->activate($this);
		}

		$this->setNotes($post['notes']);
		$this->save($changeLogEntry);
	}

	/**
	 * Returns a new address at a new location, with all the same information
	 *
	 * This is a special way of unretiring this address.
	 *
	 * @param array $post
	 * @param ChangeLogEntry $changeLogEntry
	 * @return Address
	 */
	public function reassign($post,ChangeLogEntry $changeLogEntry)
	{
		$locationData = $this->getLocation()->getUpdatableData($this);

		$newLocation = new Location();
		$newLocation->assign($this,$locationData['locationType']);
		$newLocation->update($locationData,$this);
		$newLocation->activate($this);
		$newLocation->saveStatus('CURRENT');

		$this->saveStatus('CURRENT');

		$this->setNotes($post['notes']);
		$this->save($changeLogEntry);

		return $this;
	}

	/**
	 * Sets the latest status for this address to RETIRED
	 *
	 * If there are subunits, they will be retired as well.
	 * If there are Locations with this address as the only CURRENT address,
	 * the locations are retired.
	 *
	 * You must pass in the changeLogEntry to be used for updating the
	 * subunits and locations
	 *
	 * @param array $post
	 * @param ChangeLogEntry $changeLogEntry
	 */
	public function retire($post,ChangeLogEntry $changeLogEntry)
	{
		$retired = new AddressStatus('RETIRED');
		$this->saveStatus($retired);

		foreach ($this->getSubunits() as $subunit) {
			$subunit->retire($post,$changeLogEntry);
		}

		foreach ($this->getLocations() as $location) {
			$weShouldRetireLocation = true;
			foreach ($location->getAddresses() as $address) {
				if ($address->getStatus()->getStatus_code() != $retired->getStatus_code()) {
					$weShouldRetireLocation = false;
				}
			}
			if ($weShouldRetireLocation) {
				$location->saveStatus($retired);
			}
		}

		$this->setNotes($post['notes']);
		$this->save($changeLogEntry);
	}

	/**
	 * @param array $post
	 * @param ChangeLogEntry $changeLogEntry
	 */
	public function verify($post,ChangeLogEntry $changeLogEntry)
	{
		$this->setNotes($post['notes']);
		$this->save($changeLogEntry);
	}

	/**
	 * Creates a new address in the database and returns the new address
	 *
	 * If they post a location_id, they are creating the address at that location.
	 *
	 * @param array $post
	 * @param ChangeLogEntry $changeLogEntry
	 * @return Address
	 */
	public static function createNew(array $post,ChangeLogEntry $changeLogEntry)
	{
		// Check for required Location information
		if (!$post['location_type_id'] || !$post['mailable'] || !$post['livable']) {
			throw new Exception('locations/missingRequiredFields');
		}

		$address = new Address();
		$fields = array('street_id','street_number_prefix','street_number','street_number_suffix',
						'address_type','jurisdiction_id','township_id',
						'section','quarter_section','subdivision_id','plat_id','plat_lot_number',
						'street_address_2','city','state','zip','zipplus4','notes');
		foreach ($fields as $field) {
			if (isset($post[$field])) {
				$set = 'set'.ucfirst($field);
				$address->$set($post[$field]);
			}
		}

		$zend_db = Database::getConnection();
		$zend_db->beginTransaction();
		try {
			$address->save($changeLogEntry);
			$address->saveStatus('CURRENT');

			$type = new LocationType($post['location_type_id']);
			if (isset($post['location_id']) && $post['location_id']) {
				$location = new Location($post['location_id']);
				$location->assign($address,$type);
			}
			else {
				$location = new Location();
				$location->assign($address,$type);
				$location->activate($address);
			}
			if (!$location->getId()) {
				throw new Exception('addresses/failedCreatingLocation');
			}

			$data['mailable'] = $post['mailable'];
			$data['livable'] = $post['livable'];
			$location->update($data,$address);

			$location->saveStatus('CURRENT');
		}
		catch (Exception $e) {
			$zend_db->rollBack();
			throw $e;
		}
		$zend_db->commit();
		return $address;
	}

	/**
	 * If this address was readdressed, we return the original address
	 *
	 * Readdressing results in the old address being retired,
	 * and a new address created at the same location
	 *
	 * @return Address
	 */
	public function getOldAddress()
	{
		if ($this->getLocation()) {
			$addresses = $this->getLocation()->getAddresses(array('status'=>'RETIRED'));
			if (count($addresses)) {
				return $addresses[0];
			}
		}
	}
}
