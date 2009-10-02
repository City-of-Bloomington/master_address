<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
class Contact
{
	private $contact_id;
	private $last_name;
	private $first_name;
	private $contact_type;
	private $phone_number;
	private $agency;

	private static $types = array('GIS','CBU BILLING','ADDRESS COORDINATOR','E911 ADMINISTRATOR');

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

		if (!in_array($this->contact_type,self::$types)) {
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
		$data['last_name'] = $this->last_name;
		$data['first_name'] = $this->first_name;
		$data['contact_type'] = $this->contact_type;
		$data['phone_number'] = $this->phone_number;
		$data['agency'] = $this->agency;

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
	public function getId()
	{
		return $this->getContact_id();
	}

	/**
	 * @return int
	 */
	public function getContact_id()
	{
		return $this->contact_id;
	}

	/**
	 * @return string
	 */
	public function getLast_name()
	{
		return $this->last_name;
	}

	/**
	 * @return string
	 */
	public function getFirst_name()
	{
		return $this->first_name;
	}

	/**
	 * @return string
	 */
	public function getContact_type()
	{
		return $this->contact_type;
	}

	/**
	 * @return string
	 */
	public function getPhone_number()
	{
		return $this->phone_number;
	}

	/**
	 * @return string
	 */
	public function getAgency()
	{
		return $this->agency;
	}

	//----------------------------------------------------------------
	// Generic Setters
	//----------------------------------------------------------------

	/**
	 * @param string $string
	 */
	public function setLast_name($string)
	{
		$this->last_name = trim($string);
	}

	/**
	 * @param string $string
	 */
	public function setFirst_name($string)
	{
		$this->first_name = trim($string);
	}

	/**
	 * @param string $string
	 */
	public function setContact_type($string)
	{
		$this->contact_type = trim($string);
	}

	/**
	 * @param string $string
	 */
	public function setPhone_number($string)
	{
		$this->phone_number = trim($string);
	}

	/**
	 * @param string $string
	 */
	public function setAgency($string)
	{
		$this->agency = trim($string);
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
	 * Returns the list of valid types for contacts
	 *
	 * @return array
	 */
	public static function getTypes()
	{
		return self::$types;
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
		$changeLog = array();

		$zend_db = Database::getConnection();
		$sql = " select * from ((select 'Address' as type,street_address_id as id,action,action_date,notes,user_id,contact_id
				from address_change_log where contact_id=?)
				union
				(select 'Street' as type,street_id as id,action,action_date,notes,user_id,contact_id
				from street_change_log where contact_id=?)
				union
				(select 'Subunit' as type,subunit_id as id,action,action_date,notes,user_id,contact_id
				from subunit_change_log where contact_id=?)) order by action_date DESC ";
		$result = $zend_db->fetchAll($sql,array($this->contact_id,$this->contact_id,$this->contact_id));
		foreach ($result as $row) {
			$changeLog[] = new ChangeLogEntry($row);
		}
		return $changeLog;
	}
}
