<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
class Address
{
	private $street_address_id;
	private $street_number;
	private $street_id;
	private $address_type;
	private $tax_jurisdiction;
	private $jurisdiction_id;	// jurisdiction_id is unused
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
	private $census_block_fips_code;
	private $state_plane_x_coordinate;
	private $state_plane_y_coordinate;
	private $latitude;
	private $longitude;
	private $notes;

	private $status_code;	// Used for pre-loading the latest status
	private $description; 	// Used for pre-loading the latest status
	private $status;	// Stores the latest AddressStatus object

	private $trash_pickup_day;	// Comes from mast_address_sanitation
	private $recycle_week;		// Comes from mast_address_sanitation

	private $street;
	private $jurisdiction;
	private $govJur;
	private $township;
	private $subdivision;
	private $plat;

	private $location;
	private $subunits;

	private static $addressTypes = array("STREET","UTILITY","PROPERTY",
										"PARCEL","FACILITY","TEMPORARY");
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
				$sql = "select a.*,trash_pickup_day,recycle_week,l.status_code,l.description
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
		}
	}

	/**
	 * Throws an exception if anything's wrong
	 * @throws Exception $e
	 */
	public function validate()
	{
		// Check for required fields here.  Throw an exception if anything is missing.
		if (!$this->street_id || !$this->address_type || !$this->gov_jur_id) {
			throw new Exception('missingRequiredFields');
		}
	}

	/**
	 * Saves this record back to the database
	 * @param Rationale $rationale Data for the change log entry
	 */
	public function save(ChangeLogEntry $changeLogEntry)
	{
		$this->validate();

		$data = array();
		$data['a']['street_number'] = $this->street_number ? $this->street_number : null;
		$data['a']['street_id'] = $this->street_id;
		$data['a']['address_type'] = $this->address_type;
		$data['a']['tax_jurisdiction'] = $this->tax_jurisdiction ? $this->tax_jurisdiction : null;
		$data['a']['jurisdiction_id'] = $this->jurisdiction_id;
		$data['a']['gov_jur_id'] = $this->gov_jur_id;
		$data['a']['township_id'] = $this->township_id ? $this->township_id : null;
		$data['a']['section'] = $this->section ? $this->section : null;
		$data['a']['quarter_section'] = $this->quarter_section ? $this->quarter_section : null;
		$data['a']['subdivision_id'] = $this->subdivision_id ? $this->subdivision_id : null;
		$data['a']['plat_id'] = $this->plat_id ? $this->plat_id : null;
		$data['a']['plat_lot_number'] = $this->plat_lot_number ? $this->plat_lot_number : null;
		$data['a']['street_address_2'] = $this->street_address_2 ? $this->street_address_2 : null;
		$data['a']['city'] = $this->city ? $this->city : null;
		$data['a']['state'] = $this->state ? $this->state : null;
		$data['a']['zip'] = $this->zip ? $this->zip : null;
		$data['a']['zipplus4'] = $this->zipplus4 ? $this->zipplus4 : null;
		$data['a']['census_block_fips_code'] = $this->census_block_fips_code ? $this->census_block_fips_code : null;
		$data['a']['state_plane_x_coordinate'] = $this->state_plane_x_coordinate ? $this->state_plane_x_coordinate : null;
		$data['a']['state_plane_y_coordinate'] = $this->state_plane_y_coordinate ? $this->state_plane_y_coordinate : null;
		$data['a']['latitude'] = $this->latitude ? $this->latitude : null;
		$data['a']['longitude'] = $this->longitude ? $this->longitude : null;
		$data['a']['notes'] = $this->notes ? $this->notes : null;
		$data['s']['trash_pickup_day'] = $this->trash_pickup_day ? $this->trash_pickup_day : null;
		$data['s']['recycle_week'] = $this->recycle_week ? $this->recycle_week : null;

		if ($this->street_address_id) {
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
		$zend_db->update('mast_address',$data['a'],"street_address_id='{$this->street_address_id}'");
		$zend_db->update('mast_address_sanitation',$data['s'],"street_address_id='{$this->street_address_id}'");
	}

	private function insert($data)
	{
		$zend_db = Database::getConnection();
		$zend_db->insert('mast_address',$data['a']);
		if (Database::getType()=='oracle') {
			$this->street_address_id = $zend_db->lastSequenceId('street_address_id_s');
		}
		else{
		     $this->street_address_id = $zend_db->lastInsertId('mast_address','street_address_id');
		}

		$data['s']['street_address_id'] = $this->street_address_id;
		$zend_db->insert('mast_address_sanitation',$data['s']);
	}

	private function updateChangeLog(ChangeLogEntry $changeLogEntry)
	{
		$logEntry = (array)$changeLogEntry;
		$logEntry['street_address_id'] = $this->street_address_id;

		$zend_db = Database::getConnection();
		$zend_db->insert('address_change_log',$logEntry);
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
	public function getStreet_number()
	{
		return $this->street_number;
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
	 * @return char
	 */
	public function getTax_jurisdiction()
	{
		return $this->tax_jurisdiction;
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
	 * @return string
	 */
	public function getCensus_block_fips_code()
	{
		return $this->census_block_fips_code;
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
		if (!$this->street) {
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
			$list = new AddressStatusChangeList();
			$list->find(array('street_address_id'=>$this->street_address_id,'current'=>$date),null,1);
			if (count($list)) {
				return $list[0];
			}
		}
	}

	/**
	 * Returns the status history for this address
	 *
	 * @return AddressStatusChangeList
	 */
	public function getStatusChangeList()
	{
		return new AddressStatusChangeList(array('street_address_id'=>$this->street_address_id));
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
		if (!$this->subunits) {
			$this->subunits = new SubunitList(array('street_address_id'=>$this->street_address_id));
		}
		return $this->subunits;
	}

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

	/**
	 * @param string $string
	 */
	public function setStreet_number($string)
	{
		$this->street_number = trim($string);
	}

	/**
	 * @param int $int
	 */
	public function setStreet_id($int)
	{
		$this->street = new Street($int);
		$this->street_id = $this->street->getId();
	}

	/**
	 * @param string $string
	 */
	public function setAddress_type($string)
	{
		$this->address_type = trim($string);
	}

	/**
	 * @param char $char
	 */
	public function setTax_jurisdiction($char)
	{
		$this->tax_jurisdiction = $char;
	}

	/**
	 * The real jurisdictions are the Governmental Jurisdictions
	 * @param number $number
	 */
	public function setJurisdiction_id($number)
	{
		$this->setGov_jur_id($number);
	}

	/**
	 * @param number $number
	 */
	public function setGov_jur_id($number)
	{
		$this->gov_jur_id = $number;
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
	 * @param number $number
	 */
	public function setSubdivision_id($number)
	{
		$this->subdivision_id = $number;
	}

	/**
	 * @param number $number
	 */
	public function setPlat_id($number)
	{
		$this->plat_id = $number;
	}

	/**
	 * @param number $number
	 */
	public function setPlat_lot_number($number)
	{
		$this->plat_lot_number = $number;
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
	 * @param string $string
	 */
	public function setZip($string)
	{
		$this->zip = trim($string);
	}

	/**
	 * @param string $string
	 */
	public function setZipplus4($string)
	{
		$this->zipplus4 = trim($string);
	}

	/**
	 * @param string $string
	 */
	public function setCensus_block_fips_code($string)
	{
		$this->census_block_fips_code = trim($string);
	}

	/**
	 * @param number $number
	 */
	public function setState_plane_x_coordinate($number)
	{
		$this->state_plane_x_coordinate = $number;
	}

	/**
	 * @param number $number
	 */
	public function setState_plane_y_coordinate($number)
	{
		$this->state_plane_y_coordinate = $number;
	}

	/**
	 * @param number $number
	 */
	public function setLatitude($number)
	{
		$this->latitude = $number;
	}

	/**
	 * @param number $number
	 */
	public function setLongitude($number)
	{
		$this->longitude = $number;
	}

	/**
	 * @param string $string
	 */
	public function setNotes($string)
	{
		$this->notes = trim($string);
	}

	/**
	 * @param string $string
	 */
	public function setTrash_pickup_day($string)
	{
		$string = trim($string);
		if (in_array($string,self::getTrashDays())) {
			$this->trash_pickup_day = $string;
		}
		else {
			$this->trash_pickup_day = null;
		}
	}

	/**
	 * @param string $string
	 */
	public function setRecycle_week($string)
	{
		$string = trim($string);
		if (in_array($string,self::getRecycleWeeks())) {
			$this->recycle_week = $string;
		}
		else {
			$this->recycle_week = null;
		}
	}

	/**
	 * @param Street_address $street_address
	 */
	public function setStreet_address($street_address)
	{
		$this->street_address_id = $street_address->getId();
		$this->street_address = $street_address;
	}

	/**
	 * @param Street $street
	 */
	public function setStreet($street)
	{
		$this->street_id = $street->getId();
		$this->street = $street;
	}

	/**
	 * @param Jurisdiction $jurisdiction
	 */
	public function setJurisdiction($jurisdiction)
	{
		$this->gov_jur_id = $jurisdiction->getId();
		$this->jurisdiction = $jurisdiction;
	}

	/**
	 * @param Gov_jur $gov_jur
	 */
	public function setGovJur($govJur)
	{
		$this->gov_jur_id = $govJur->getId();
		$this->gov_jur = $govJur;
	}

	/**
	 * @param Township $township
	 */
	public function setTownship($township)
	{
		$this->township_id = $township->getId();
		$this->township = $township;
	}

	/**
	 * @param Subdivision $subdivision
	 */
	public function setSubdivision($subdivision)
	{
		$this->subdivision_id = $subdivision->getId();
		$this->subdivision = $subdivision;
	}

	/**
	 * @param Plat $plat
	 */
	public function setPlat($plat)
	{
		$this->plat_id = $plat->getId();
		$this->plat = $plat;
	}


	//----------------------------------------------------------------
	// Custom Functions
	// We recommend adding all your custom code down here at the bottom
	//----------------------------------------------------------------
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
	 * @return string
	 */
	public function getStreetAddress()
	{
		$address = array();
		$address[] = $this->getStreet_number();
		$address[] = $this->getStreet()->getDirection()->getCode();
		$address[] = $this->getStreet()->getStreetName()->getStreet_name();
		$address[] = $this->getStreet()->getPostDirection()->getCode();
		$address = implode(' ',$address);
		return preg_replace('/\s+/',' ',$address);
	}

	/**
	 * @return LocationList
	 */
	public function getLocations(array $fields=null)
	{
		$search = array('street_address_id'=>$this->street_address_id);
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
			$list = new LocationList(array('street_address_id'=>$this->street_address_id,
											'subunit_id'=>null,
											'active'=>'Y'));
			if (count($list)) {
				$this->location = $list[0];
			}
		}
		return $this->location;
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
}
