<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
class Street
{
	private $street_id;
	private $street_direction_code;
	private $post_direction_suffix_code;
	private $town_id;
	private $status_code;
	private $notes;


	private $town;
	private $status;
	private $direction;  // street_direction_code
	private $postDirection; // post_direction_suffix_code
	private $streetNameList;
	private $streetName;
	private $addresses;


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
	 * @param int|array $street_id
	 */
	public function __construct($street_id=null)
	{
		if ($street_id) {
			if (is_array($street_id)) {
				$result = $street_id;
			}
			else {
				$zend_db = Database::getConnection();
				$sql = 'select * from mast_street where street_id=?';
				$result = $zend_db->fetchRow($sql,array($street_id));
			}

			if ($result) {
				foreach ($result as $field=>$value) {
					if ($value) {
						$this->$field = $value;
					}
				}
			}
			else {
				throw new Exception('streets/unknownMastStreet');
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
		if (!$this->status_code) {
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
		$data['street_direction_code'] = $this->street_direction_code ? $this->street_direction_code : null;
		$data['post_direction_suffix_code'] = $this->post_direction_suffix_code ? $this->post_direction_suffix_code : null;
		$data['town_id'] = $this->town_id ? $this->town_id : null;
		$data['status_code'] = $this->status_code;
		$data['notes'] = $this->notes ? $this->notes : null;

		if ($this->street_id) {
			$this->update($data);
		}
		else {
			$this->insert($data);
		}
	}

	private function update($data)
	{
		$zend_db = Database::getConnection();
		$zend_db->update('mast_street',$data,"street_id='{$this->street_id}'");
	}

	private function insert($data)
	{
		$zend_db = Database::getConnection();
		$zend_db->insert('mast_street',$data);
		if (Database::getType()=='oracle') {
			$this->street_id = $zend_db->lastSequenceId('street_id_s');
		}
		else {
		  $this->street_id = $zend_db->lastInsertId('mast_street','street_id');
		}
	}

	//----------------------------------------------------------------
	// Generic Getters
	//----------------------------------------------------------------

	/**
	 * @return number
	 */
	public function getStreet_id()
	{
		return $this->street_id;
	}

	/**
	 * @return number
	 */
	public function getId()
	{
		return $this->street_id;
	}

	/**
	 * @return char
	 */
	public function getStreet_direction_code()
	{
		return $this->street_direction_code;
	}

	/**
	 * @return char
	 */
	public function getPost_direction_suffix_code()
	{
		return $this->post_direction_suffix_code;
	}

	/**
	 * @return number
	 */
	public function getTown_id()
	{
		return $this->town_id;
	}

	/**
	 * @return number
	 */
	public function getStatus_code()
	{
		return $this->status_code;
	}

	/**
	 * @return string
	 */
	public function getNotes()
	{
		return $this->notes;
	}

	/**
	 * @return Town
	 */
	public function getTown()
	{
		if ($this->town_id) {
			if (!$this->town) {
				$this->town = new Town($this->town_id);
			}
			return $this->town;
		}
		return new Town();
	}

	/**
	 * @return StreetStatus
	 */
	public function getStatus()
	{
		if ($this->status_code) {
			if (!$this->status) {
				$this->status = new StreetStatus($this->status_code);
			}
			return $this->status;
		}
		return new StreetStatus();
	}

	//----------------------------------------------------------------
	// Generic Setters
	//----------------------------------------------------------------

	/**
	 * @param char $char
	 */
	public function setStreet_direction_code($char)
	{
		$this->direction = new Direction($char);
		$this->street_direction_code = $this->direction->getCode();
	}

	/**
	 * @param char $char
	 */
	public function setPost_direction_suffix_code($char)
	{
		$this->postDirection = new Direction($char);
		$this->post_direction_suffix_code = $this->postDirection->getCode();
	}

	/**
	 * @param number $number
	 */
	public function setTown_id($number)
	{
		$this->town = new Town($number);
		$this->town_id = $this->town->getId();
	}

	/**
	 * @param number $number
	 */
	public function setStatus_code($number)
	{
		$this->status = new StreetStatus($number);
		$this->status_code = $this->status->getCode();
	}

	/**
	 * @param string $string
	 */
	public function setNotes($string)
	{
		$this->notes = trim($string);
	}

	/**
	 * @param Town $town
	 */
	public function setTown($town)
	{
		$this->town_id = $town->getId();
		$this->town = $town;
	}

	/**
	 * @param StreetStatus $status
	 */
	public function setStatus($status)
	{
		$this->street_status_code = $status->getCode();
		$this->streetStatus = $streetStatus;
	}

	/**
	 * @param Direction $direction
	 */
	public function setDirection($direction)
	{
		$this->street_direction_code = $direction->getId();
		$this->direction = $direction;
	}

	/**
	 * @param Direction $suffix
	 */
	public function setPostDirection($direction)
	{
		$this->post_direction_suffix_code = $direction->getId();
		$this->postDirection = $direction;
	}
	//----------------------------------------------------------------
	// Custom Functions
	// We recommend adding all your custom code down here at the bottom
	//----------------------------------------------------------------
	/**
	 * @return Direction
	 */
	public function getDirection()
	{
		if ($this->street_direction_code) {
			if (!$this->direction) {
				$this->direction = new Direction($this->street_direction_code);
			}
			return $this->direction;
		}
		return new Direction();
	}

	/**
	 * @return Direction
	 */
	public function getPostDirection()
	{
		if ($this->post_direction_suffix_code) {
			if (!$this->postDirection) {
				$this->postDirection = new Direction($this->post_direction_suffix_code);
			}
			return $this->postDirection;
		}
		return new Direction();
	}

	/**
	 * @return StreetNameList
	 */
	public function getStreetNameList()
	{
		if ($this->street_id) {
			if (!$this->streetNameList) {
			    $streetNameList = new StreetNameList(array('street_id'=>$this->street_id));
				$this->streetNameList = $streetNameList;
			}
			return $this->streetNameList;
		}
		return null;
	}

	/**
	 * @return StreetNameList
	 */

	public function getNames()
	{
	  return  $this->getStreetNameList();
	}


	/**
	 * @return AddressList
	 */
	public function getAddresses()
	{
		if ($this->street_id) {
			if (!$this->addresses) {
			    $addresses = new AddressList(array('street_id'=>$this->street_id));
				$this->addresses = $addresses;
			}
			return $this->addresses;
		}
		return null;
	}
	/**
	 * Returns the primary name for this street
	 *
	 * @return StreetName
	 */
	public function getStreetName()
	{
		if (!$this->streetName) {
			$streetNameList = new StreetNameList(array('street_id'=>$this->street_id,
														'street_name_type'=>'STREET'));
			if (count($streetNameList)) {
				$this->streetName = $streetNameList[0];
			}
			else {
				# We couldn't find a name of the TYPE:STREET for this street.
				# Do another search and see if we can find any name at all
				$streetNameList = new StreetNameList(array('street_id'=>$this->street_id));
				if (count($streetNameList)) {
					$this->streetName = $streetNameList[0];
				}
			}
		}
		return $this->streetName;
	}

	/**
	 * @return string
	 */
	public function getURL()
	{
		return BASE_URL.'/streets/viewStreet.php?street_id='.$this->street_id;
	}
}
