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
	 * @param StreetStatus|string $status
	 */
	public function setStatus($status)
	{
		if (!$status instanceof StreetStatus) {
			$status = new StreetStatus($status);
		}
		$this->status_code = $status->getCode();
		$this->streetStatus = $status;
	}

	//----------------------------------------------------------------
	// Custom Functions
	// We recommend adding all your custom code down here at the bottom
	//----------------------------------------------------------------
	public function __toString()
	{
		return "{$this->getStreetName()}";
	}

	/**
	 * @return StreetNameList
	 */
	public function getStreetNameList()
	{
		return new StreetNameList(array('street_id'=>$this->street_id));
	}

	/**
	 * @return StreetNameList
	 */
	public function getNames()
	{
		return $this->getStreetNameList();
	}

	/**
	 * Returns the primary name for this street
	 *
	 * If it can't find a primary name, we see if we can return any name at all.
	 *
	 * @return StreetName
	 */
	public function getStreetName()
	{
		if ($this->street_id) {
			$streetNameList = new StreetNameList(array('street_id'=>$this->street_id,
														'street_name_type'=>'STREET'));
			if (count($streetNameList)) {
				return $streetNameList[0];
			}
			else {
				// We couldn't find a name of the TYPE:STREET for this street.
				// Do another search and see if we can find any name at all
				$streetNameList = new StreetNameList(array('street_id'=>$this->street_id));
				if (count($streetNameList)) {
					return $streetNameList[0];
				}
			}
		}
	}

	/**
	 * Creates a new name for a street
	 *
	 * @param array $post
	 * @param ChangeLogEntry $changeLogEntry
	 */
	public function addStreetName(array $post,ChangeLogEntry $changeLogEntry)
	{
		$fields = array('street_name','street_type_suffix_code','street_name_type',
						'effective_start_date','effective_end_date','notes',
						'street_direction_code','post_direction_suffix_code');
		$streetName = new StreetName();
		$streetName->setStreet($this);
		foreach ($fields as $field) {
			if (isset($post[$field])) {
				$set = 'set'.ucfirst($field);
				$streetName->$set($post[$field]);
			}
		}
		$streetName->save();
		$this->updateChangeLog($changeLogEntry);
	}

	/**
	 * Creates a new StreetName with type:"STREET"
	 * Sets any old StreetNames of type:"STREET" to type:"HISTORIC"
	 */
	public function changeStreetName(array $post,ChangeLogEntry $changeLogEntry)
	{
		$zend_db = Database::getConnection();
		$zend_db->beginTransaction();

		try {
			$fields = array('street_name','street_type_suffix_code',
							'effective_start_date','effective_end_date','notes',
							'street_direction_code','post_direction_suffix_code');
			$streetName = new StreetName();
			$streetName->setStreet($this);
			$streetName->setStreet_name_type('STREET');
			foreach ($fields as $field) {
				if (isset($post['streetName'][$field])) {
					$set = 'set'.ucfirst($field);
					$streetName->$set($post['streetName'][$field]);
				}
			}
			$streetName->save();

			$zend_db->update('mast_street_names',
							array('street_name_type'=>'HISTORIC',
									'effective_end_date'=>date('Y-m-d')),
							"street_id={$this->street_id}
							and street_name_type='STREET'
							and id!={$streetName->getId()}");

			$this->updateChangeLog($changeLogEntry);

			// If they post new address street numbers, apply the new street numbers
			// and save the changeLog for each address
			if (isset($post['addresses'])) {
				foreach ($post['addresses'] as $id=>$number) {
					$address = new Address($id);
					$address->setStreet_number($number);
					$address->save($changeLogEntry);
				}
			}
			else {
				// Otherwise, just save the changeLogEntry against all the addresses
				foreach ($this->getAddresses() as $address) {
					$address->updateChangeLog($changeLogEntry);
				}
			}
		}
		catch (Exception $e) {
			$zend_db->rollBack();
			throw $e;
		}
		$zend_db->commit();
	}

	/**
	 * @param array $fields Extra fields to search on
	 * @return AddressList
	 */
	public function getAddresses(array $fields=null)
	{
		if ($this->street_id) {
			$search = array('street_id'=>$this->street_id);
			if ($fields) {
				$search = array_merge($search,$fields);
			}
		    return new AddressList($search);
		}
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
		if ($this->street_id) {
			$zend_db = Database::getConnection();
			$sql = 'select * from street_change_log where street_id=? order by action_date';
			$result = $zend_db->fetchAll($sql,$this->street_id);
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
		if ($this->street_id) {
			$zend_db = Database::getConnection();
			$select = $zend_db->select()->from('street_change_log');
			$select->where('street_id=?',$this->street_id);
			$select->order('action_date desc');

			return new Zend_Paginator(new Zend_Paginator_Adapter_DbSelect($select));
		}
	}

	/**
	 * @param array $post
	 * @param ChangeLogEntry $changeLogEntry
	 */
	public function correct($post,ChangeLogEntry $changeLogEntry)
	{
		$fields = array('town_id','notes');
		foreach ($fields as $field) {
			if (isset($post[$field])) {
				$set = 'set'.ucfirst($field);
				$this->$set($post[$field]);
			}
		}
		$this->save($changeLogEntry);
	}

	/**
	 * Sets the latest status for this street to RETIRED
	 *
	 * @param ChangeLogEntry $changeLogEntry
	 */
	public function retire(ChangeLogEntry $changeLogEntry)
	{
		$this->saveStatus('RETIRED');
		$this->updateChangeLog($changeLogEntry);
	}

	/**
	 * Sets the latest status for this street to CURRENT
	 *
	 * @param ChangeLogEntry $changeLogEntry
	 */
	public function unretire(ChangeLogEntry $changeLogEntry)
	{
		$this->saveStatus('CURRENT');
		$this->updateChangeLog($changeLogEntry);
	}

	/**
	 * @param ChangeLogEntry $changeLogEntry
	 */
	public function verify(ChangeLogEntry $changeLogEntry)
	{
		$this->updateChangeLog($changeLogEntry);
	}

	/**
	 * Creates a new street in the database and returns the new street
	 *
	 * @param array $post
	 * @param ChangeLogEntry $changeLogEntry
	 * @return Street
	 */
	public static function createNew(array $post,ChangeLogEntry $changeLogEntry)
	{
		$zend_db = Database::getConnection();
		$zend_db->beginTransaction();

		try {
			$street = new Street();
			$street->setTown_id($post['town_id']);
			$street->setNotes($post['notes']);

			switch ($changeLogEntry->action) {
				case 'propose':
					$street->setStatus('PROPOSED');
					break;
				default:
					$street->setStatus('CURRENT');
			}
			$street->save($changeLogEntry);

			if (isset($post['streetName'])) {
				$streetName = new StreetName();
				$streetName->setStreet_name_type('STREET');

				$fields = array('street_direction_code','street_name','street_type_suffix_code',
								'post_direction_suffix_code','notes',
								'effective_start_date','effective_end_date');
				foreach ($fields as $field) {
					if (isset($post['streetName'][$field])) {
						$set = 'set'.ucfirst($field);
						$streetName->$set($post['streetName'][$field]);
					}
				}

				$streetName->setStreet_id($street->getId());
				$streetName->save();
			}
			$zend_db->commit();

			return $street;
		}
		catch (Exception $e) {
			$zend_db->rollBack();
			throw $e;
		}
	}
}
