<?php
/**
 * @copyright 2009-2014 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
class Contact implements ChangeLogInterface
{
	private $contact_id;
	private $last_name;
	private $first_name;
	private $contact_type;
	private $phone_number;
	private $agency;
	private $email;
	private $address;
	private $city;
	private $state;
	private $zip;
	private $notification;
	private $coordination;
	private $status_id;

	private $status;

	private static $types = [
		'Address Coordinator', 'CBU', 'Developer', 'E911 Administrator', 'Emergency Services',
		'GIS', 'Government Agency', 'Property Owner', 'Utility Provider'
	];
	private static $statuses;

    /**
     * Returns the list of valid types for contacts
     *
     * @return array
     */
    public static function getTypes()
    {
        if (!self::$types) {
			// I've preset the current list of type, so we can avoid this
			// extra database call.
            $sql = 'select distinct contact_type from mast_addr_assignment_contact order by contact_type';
            $zend_db = Database::getConnection();
            self::$types = $zend_db->fetchCol($sql);
        }
        return self::$types;
    }

    /**
     * @return array
     */
    public static function getStatuses()
    {
        if (!self::$statuses) {
            $sql = 'select * from eng.contactstatus';
            $zend_db = Database::getConnection();
            $result = $zend_db->fetchAll($sql);
            foreach ($result as $row) {
                self::$statuses[] = new ContactStatus($row);
            }
        }
        return self::$statuses;
    }

    public static function getNotificationValues() { return ['Y']; }
    public static function getCoordinationValues() { return ['Y', 'CC']; }

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
	 * @param int|array $contact_id
	 */
	public function __construct($contact_id=null)
	{
		if ($contact_id) {
			if (is_array($contact_id)) {
				$result = $contact_id;
			}
			else {
				$zend_db = Database::getConnection();
				$sql = 'select * from mast_addr_assignment_contact where contact_id=?';
				$result = $zend_db->fetchRow($sql,array($contact_id));
			}

			if ($result) {
				foreach ($result as $field=>$value) {
					if ($value) {
						$this->$field = $value;
					}
				}
			}
			else {
				throw new Exception('contacts/unknownContact');
			}
		}
		else {
			// This is where the code goes to generate a new, empty instance.
			// Set any default values for properties that need it here
			$this->setStatus(new ContactStatus('Current'));
		}
	}

	/**
	 * Throws an exception if anything's wrong
	 * @throws Exception $e
	 */
	public function validate()
	{
		// Check for required fields here.  Throw an exception if anything is missing.
		if (!$this->last_name || !$this->first_name || !$this->contact_type
			|| !$this->phone_number || !$this->agency) {
			throw new Exception('missingRequiredFields');
		}

		if (!in_array($this->contact_type,self::getTypes())) {
			throw new Exception('contacts/invalidType');
		}
	}

	/**
	 * Saves this record back to the database
	 */
	public function save()
	{
		$this->validate();

		$data = array();
		$data['last_name']    = $this->last_name;
		$data['first_name']   = $this->first_name;
		$data['contact_type'] = $this->contact_type;
		$data['phone_number'] = $this->phone_number;
		$data['agency']       = $this->agency;
        $data['status_id']    = $this->status_id;
        $data['email']        = $this->email        ? $this->email        : null;
        $data['address']      = $this->address      ? $this->address      : null;
        $data['city']         = $this->city         ? $this->city         : null;
        $data['state']        = $this->state        ? $this->state        : null;
        $data['zip']          = $this->zip          ? $this->zip          : null;
        $data['notification'] = $this->notification ? $this->notification : null;
        $data['coordination'] = $this->coordination ? $this->coordination : null;

		if ($this->contact_id) {
			$this->update($data);
		}
		else {
			$this->insert($data);
		}
	}

	private function update($data)
	{
		$zend_db = Database::getConnection();
		$zend_db->update('mast_addr_assignment_contact',$data,"contact_id='{$this->contact_id}'");
	}

	private function insert($data)
	{
		$zend_db = Database::getConnection();
		$zend_db->insert('mast_addr_assignment_contact',$data);
		if (Database::getType()=='oracle') {
		  $this->contact_id = $zend_db->lastSequenceId('contact_id_s');
		}
		else{
		  $this->contact_id = $zend_db->lastInsertId();
		}
	}

	//----------------------------------------------------------------
	// Generic Getters
	//----------------------------------------------------------------
	/**
	 * Alias of getConact_id()
	 *
	 * @return int
	 */
	public function getId() { return $this->getContact_id(); }

	public function getContact_id()   { return $this->contact_id; }
	public function getLast_name()    { return $this->last_name; }
	public function getFirst_name()   { return $this->first_name; }
	public function getContact_type() { return $this->contact_type; }
	public function getPhone_number() { return $this->phone_number; }
	public function getAgency()       { return $this->agency; }
	public function getEmail()        { return $this->email; }
	public function getAddress()      { return $this->address; }
	public function getCity()         { return $this->city; }
	public function getState()        { return $this->state; }
	public function getZip()          { return $this->zip; }
	public function getNotification() { return $this->notification; }
	public function getCoordination() { return $this->coordination; }

    public function getStatus_id() { return $this->status_id; }
    public function getStatus()
    {
        if (!$this->status) {
            $this->status = new ContactStatus($this->status_id);
        }
        return $this->status;
    }


	//----------------------------------------------------------------
	// Generic Setters
	//----------------------------------------------------------------
	public function setLast_name   ($s) { $this->last_name    = trim($s); }
	public function setFirst_name  ($s) { $this->first_name   = trim($s); }
	public function setContact_type($s) { $this->contact_type = trim($s); }
	public function setPhone_number($s) { $this->phone_number = strtoupper(trim($s)); }
	public function setAgency      ($s) { $this->agency       = trim($s); }
	public function setEmail       ($s) { $this->email        = trim($s); }
	public function setAddress     ($s) { $this->address      = trim($s); }
	public function setCity        ($s) { $this->city         = trim($s); }
	public function setState       ($s) { $this->state        = trim($s); }
	public function setZip         ($s) { $this->zip          = trim($s); }
	public function setNotification($s) { $this->notification = $s ? trim($s) : null; }
	public function setCoordination($s) { $this->coordination = $s ? trim($s) : null; }

	public function setStatus($s) {
        if ($s instanceof ContactStatus) {
            $this->status_id = $s->getId();
            $this->status = $s;
        }
        else {
            $this->status = new ContactStatus($s);
            $this->status_id = $this->status->getId();
        }
	}

	/**
	 * @param array $post The POST array
	 */
	public function handleUpdate($post)
	{
		$fields = [
			'status', 'contact_type', 'agency',
			'last_name', 'first_name', 'email', 'phone_number',
			'address', 'city', 'state', 'zip',
			'notification', 'coordination'
		];
		foreach ($fields as $f) {
			$set = 'set'.ucfirst($f);
			$this->$set($post[$f]);
		}
	}

	//----------------------------------------------------------------
	// Custom Functions
	// We recommend adding all your custom code down here at the bottom
	//----------------------------------------------------------------
	/**
	 * Alias for getLast_name()
	 *
	 * @return string
	 */
	public function getLastname()
	{
		return $this->getLast_name();
	}

	/**
	 * Alias for getFirst_name()
	 *
	 * @return string
	 */
	public function getFirstname()
	{
		return $this->getFirst_name();
	}

	/**
	 * Alias for getContact_type()
	 *
	 * @return string
	 */
	public function getType()
	{
		return $this->getContact_type();
	}

	/**
	 * Returns an array of ChangeLogEntries
	 *
	 * @return array
	 */
	public function getChangeLog()
	{
		if ($this->contact_id) {
			return ChangeLog::getEntries(ChangeLog::getTypes(),
										 ChangeLog::getActions(),
										 array('contact_id'=>$this->contact_id));
		}
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
		if ($this->contact_id) {
			return ChangeLog::getPaginator(ChangeLog::getTypes(),
											ChangeLog::getActions(),
											array('contact_id'=>$this->contact_id));
		}
	}
}
