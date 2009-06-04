<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
class MastAddress
{
	private $street_address_id;
	private $street_number;
	private $street_id;
	private $address_type;
	private $tax_jurisdiction;
	private $jurisdiction_id;
	private $gov_jur_id;
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
	private $status_code;


	private $street;
	private $jurisdiction;
	private $govJur;
	private $township;
	private $subdivision;
	private $plat;



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
				$sql = 'select * from mast_address where street_address_id=?';
				$result = $zend_db->fetchRow($sql,array($street_address_id));
			}

			if ($result) {
				foreach ($result as $field=>$value) {
					if ($value) {
						$this->$field = $value;
					}
				}
			}
			else {
				throw new Exception('mast_address/unknownMastAddress');
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
		$data['street_number'] = $this->street_number ? $this->street_number : null;
		$data['street_id'] = $this->street_id ? $this->street_id : null;
		$data['address_type'] = $this->address_type ? $this->address_type : null;
		$data['tax_jurisdiction'] = $this->tax_jurisdiction ? $this->tax_jurisdiction : null;
		$data['jurisdiction_id'] = $this->jurisdiction_id ? $this->jurisdiction_id : null;
		$data['gov_jur_id'] = $this->gov_jur_id ? $this->gov_jur_id : null;
		$data['township_id'] = $this->township_id ? $this->township_id : null;
		$data['section'] = $this->section ? $this->section : null;
		$data['quarter_section'] = $this->quarter_section ? $this->quarter_section : null;
		$data['subdivision_id'] = $this->subdivision_id ? $this->subdivision_id : null;
		$data['plat_id'] = $this->plat_id ? $this->plat_id : null;
		$data['plat_lot_number'] = $this->plat_lot_number ? $this->plat_lot_number : null;
		$data['street_address_2'] = $this->street_address_2 ? $this->street_address_2 : null;
		$data['city'] = $this->city ? $this->city : null;
		$data['state'] = $this->state ? $this->state : null;
		$data['zip'] = $this->zip ? $this->zip : null;
		$data['zipplus4'] = $this->zipplus4 ? $this->zipplus4 : null;
		$data['census_block_fips_code'] = $this->census_block_fips_code ? $this->census_block_fips_code : null;
		$data['state_plane_x_coordinate'] = $this->state_plane_x_coordinate ? $this->state_plane_x_coordinate : null;
		$data['state_plane_y_coordinate'] = $this->state_plane_y_coordinate ? $this->state_plane_y_coordinate : null;
		$data['latitude'] = $this->latitude ? $this->latitude : null;
		$data['longitude'] = $this->longitude ? $this->longitude : null;
		$data['notes'] = $this->notes ? $this->notes : null;
		$data['status_code'] = $this->status_code ? $this->status_code : null;

		if ($this->street_address_id) {
			$this->update($data);
		}
		else {
			$this->insert($data);
		}
	}

	private function update($data)
	{
		$zend_db = Database::getConnection();
		$zend_db->update('mast_address',$data,"street_address_id='{$this->street_address_id}'");
	}

	private function insert($data)
	{
		$zend_db = Database::getConnection();
		$zend_db->insert('mast_address',$data);
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
	 * @return number
	 */
	public function getStreet_address_id()
	{
		return $this->street_address_id;
	}

	/**
	 * @return number
	 */
	public function getId()
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
	 * @return number
	 */
	public function getJurisdiction_id()
	{
		return $this->jurisdiction_id;
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
	 * @return string
	 */
	public function getNotes()
	{
		return $this->notes;
	}

	/**
	 * @return number
	 */
	public function getStatus_code()
	{
		return $this->status_code;
	}

	/**
	 * @return Street
	 */
	public function getStreet()
	{
		if ($this->street_id) {
			if (!$this->street) {
				$this->street = new Street($this->street_id);
			}
			return $this->street;
		}
		return null;
	}

	/**
	 * @return Jurisdiction
	 */
	public function getJurisdiction()
	{
		if ($this->jurisdiction_id) {
			if (!$this->jurisdiction) {
				$this->jurisdiction = new Jurisdiction($this->jurisdiction_id);
			}
			return $this->jurisdiction;
		}
		return null;
	}

	/**
	 * @return Gov_jur
	 */
	public function getGovJur()
	{
		if ($this->gov_jur_id) {
			if (!$this->govJur) {
				$this->govJur = new GovJur($this->gov_jur_id);
			}
			return $this->govJur;
		}
		return null;
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
	 * @param number $number
	 */
	public function setStreet_id($number)
	{
		$this->street_id = $number;
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
	 * @param number $number
	 */
	public function setJurisdiction_id($number)
	{
		$this->jurisdiction_id = $number;
	}

	/**
	 * @param number $number
	 */
	public function setGov_jur_id($number)
	{
		$this->gov_jur_id = $number;
	}

	/**
	 * @param number $number
	 */
	public function setTownship_id($number)
	{
		$this->township_id = $number;
	}

	/**
	 * @param string $string
	 */
	public function setSection($string)
	{
		$this->section = trim($string);
	}

	/**
	 * @param char $char
	 */
	public function setQuarter_section($char)
	{
		$this->quarter_section = $char;
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
	 * @param number $number
	 */
	public function setStatus_code($number)
	{
		$this->status_code = $number;
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
		$this->jurisdiction_id = $jurisdiction->getId();
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
}
