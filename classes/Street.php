<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
class Street
{
	// The direction and post-direction in this table are bad data.
	// They are left over from long ago.
	// The direction and post-direction for a street should come from whatever
	// StreetName is being used for that street.
	private $street_id;
	private $street_direction_code;			// Unused - see StreetName class
	private $post_direction_suffix_code;	// Unused - see StreetName class
	private $town_id;
	private $status_code;
	private $notes;

	private $town;
	private $status;
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
	 *
	 * @param ChangeLogEntry $changeLogEntry
	 */
	public function save(ChangeLogEntry $changeLogEntry)
	{
		$this->validate();

		$data = array();
		$data['town_id'] = $this->town_id ? $this->town_id : null;
		$data['status_code'] = $this->status_code;
		$data['notes'] = $this->notes ? $this->notes : null;

		if ($this->street_id) {
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
	 * Alias for getStreet_id()
	 *
	 * @return int
	 */
	public function getId()
	{
		return $this->street_id;
	}

	/**
	 * @return int
	 */
	public function getStreet_id()
	{
		return $this->street_id;
	}


	/**
	 * @return int
	 */
	public function getTown_id()
	{
		return $this->town_id;
	}

	/**
	 * @return int
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

	//----------------------------------------------------------------
	// Custom Functions
	// We recommend adding all your custom code down here at the bottom
	//----------------------------------------------------------------

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
		return $this->getStreetNameList();
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

	/**
	 * Saves a ChangeLogEntry to the database for this this street
	 *
	 * @param ChangeLogEntry $changeLogEntry
	 */
	public function updateChangeLog(ChangeLogEntry $changeLogEntry)
	{
		$logEntry = $changeLogEntry->getData();
		$logEntry['street_id'] = $this->street_id;

		$zend_db = Database::getConnection();
		$zend_db->insert('street_change_log',$logEntry);
	}

	/**
	 * Returns an array of ChangeLogEntries
	 *
	 * @return array
	 */
	public function getChangeLog()
	{
		$changeLog = array();

		$zend_db = Database::getConnection();
		$sql = 'select * from street_change_log where street_id=? order by action_date desc';
		$result = $zend_db->fetchAll($sql,$this->street_id);
		foreach ($result as $row) {
			$changeLog[] = new ChangeLogEntry($row);
		}
		return $changeLog;
	}
}
