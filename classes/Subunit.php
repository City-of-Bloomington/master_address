<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
class Subunit
{
	private $subunit_id;
	private $street_address_id;
	private $sudtype;
	private $street_subunit_identifier;
	private $notes;

	private $subunitType;
	private $address;

	private $status_code;	// Used for pre-loading the latest status
	private $description; 	// Used for pre-loading the latest status
	private $status;	// Stores the latest AddressStatus object


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
				$sql = "select s.*,l.status_code,l.description
						from mast_address_subunits s
						left join latest_subunit_status l on s.subunit_id=l.subunit_id
						where s.subunit_id=?";
				$result = $zend_db->fetchRow($sql,array($subunit_id));
			}

			if ($result) {
				foreach ($result as $field=>$value) {
					if ($value) {
						$this->$field = $value;
					}
				}
				$this->status = new AddressStatus(array('status_code'=>$this->status,
														'description'=>$this->description));
			}
			else {
				throw new Exception('subunits/unknownSubunit');
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
		if (!$this->street_address_id || !$this->sudtype || !$this->street_subunit_identifier) {
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
		$data['street_address_id'] = $this->street_address_id;
		$data['sudtype'] = $this->sudtype;
		$data['street_subunit_identifier'] = $this->street_subunit_identifier;
		$data['notes'] = $this->notes ? $this->notes : null;

		if ($this->subunit_id) {
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
		$zend_db->update('mast_address_subunits',$data,"subunit_id='{$this->subunit_id}'");
	}

	private function insert($data)
	{
		$zend_db = Database::getConnection();
		$zend_db->insert('mast_address_subunits',$data);
		if (Database::getType()=='oracle') {
			$this->subunit_id = $zend_db->lastSequenceId('subunit_id_s');
		}
		else {
			$this->subunit_id = $zend_db->lastInsertId('mast_address_subunits','subunit_id');
		}

	}

	public function updateChangeLog(ChangeLogEntry $changeLogEntry)
	{
		$logEntry = $changeLogEntry->getData();
		$logEntry['subunit_id'] = $this->subunit_id;

		$zend_db = Database::getConnection();
		$zend_db->insert('subunit_change_log',$logEntry);
	}
	//----------------------------------------------------------------
	// Generic Getters
	//----------------------------------------------------------------
	/**
	 * Alias of getSubunit_id()
	 *
	 * @return int
	 */
	public function getId()
	{
		return $this->subunit_id;
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
	 * @return string
	 */
	public function getSudtype()
	{
		return $this->sudtype;
	}

	/**
	 * @return string
	 */
	public function getStreet_subunit_identifier()
	{
		return $this->street_subunit_identifier;
	}

	/**
	 * @return string
	 */
	public function getNotes()
	{
		return $this->notes;
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
	 * @return SubunitType
	 */
	public function getSubunitType()
	{
		if (!$this->subunitType) {
			$this->subunitType = new SubunitType($this->sudtype);
		}
		return $this->subunitType;
	}

	//----------------------------------------------------------------
	// Generic Setters
	//----------------------------------------------------------------

	/**
	 * @param int $number
	 */
	public function setStreet_address_id($number)
	{
		$this->street_address_id = $number;
	}

	/**
	 * @param string $string
	 */
	public function setSudtype($string)
	{
		$this->sudtype = trim($string);
	}

	/**
	 * @param string $string
	 */
	public function setStreet_subunit_identifier($string)
	{
		$this->street_subunit_identifier = trim($string);
	}

	/**
	 * @param string $string
	 */
	public function setNotes($string)
	{
		$this->notes = trim($string);
	}

	/**
	 * @param Address $address
	 */
	public function setAddress($address)
	{
		$this->street_address_id = $address->getId();
		$this->address = $address;
	}

	//----------------------------------------------------------------
	// Custom Functions
	// We recommend adding all your custom code down here at the bottom
	//----------------------------------------------------------------
	public function __toString()
	{
		return "{$this->getType()} {$this->getIdentifier()}";
	}
	
	/**
	 * Returns the status history for this address
	 *
	 * @return AddressStatusChangeList
	 */
	public function getStatusChangeList(array $fields=null)
	{
		$search = array('subunit_id'=>$this->subunit_id);
		if ($fields) {
			$search = array_merge($search,$fields);
		}
		return new SubunitStatusChangeList($search);
	}


	/**
	 * Alias for getSudtype()
	 *
	 * @return string
	 */
	public function getType()
	{
		return $this->getSudtype();
	}

	/**
	 * Alias for getStreet_subunit_identifier()
	 *
	 * @return string
	 */
	public function getIdentifier()
	{
		return $this->getStreet_subunit_identifier();
	}

	/**
	 * Returns the StatusChange for this subunit that was active on the given date
	 *
	 * Defaults to the current date
	 *
	 * @param Date $date
	 */
	public function getStatus(Date $date=null)
	{
		if (!$date) {
			return $this->status;
		}
		else {
			$list = new SubunitStatusChangeList();
			$list->find(array('subunit_id'=>$this->subunit_id,'current'=>$date));
			if (count($list)) {
				return $list[0];
			}
		}
	}
	/**
	 * @return string
	 */
	public function getURL()
	{
		return BASE_URL.'/subunits/viewSubunit.php?subunit_id='.$this->subunit_id;
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
		$sql = "select * from subunit_change_log where subunit_id=? order by action_date desc";
		$result = $zend_db->fetchAll($sql,$this->subunit_id);
		foreach ($result as $row) {
			$changeLog[] = new ChangeLogEntry($row);
		}
		return $changeLog;
	}

	/**
	 * Saves a new AddressStatusChange to the database
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
			$newStatus = new SubunitStatusChange();
			$newStatus->setSubunit($this);			
			$newStatus->setStreet_address_id($this->street_address_id);
			$newStatus->setStatus($status);
			$newStatus->save();
		}
		if ($currentStatus
			&& $currentStatus->getStatus_code() != $status->getStatus_code()) {
			$currentStatus->setEnd_date(time());
			$currentStatus->save();
		}
	 }
	
}
