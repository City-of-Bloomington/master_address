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
				throw new Exception('addressChange/unknownAddrAssignmentContact');
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
		$data['last_name'] = $this->last_name ? $this->last_name : null;
		$data['first_name'] = $this->first_name ? $this->first_name : null;
		$data['contact_type'] = $this->contact_type ? $this->contact_type : null;
		$data['phone_number'] = $this->phone_number ? $this->phone_number : null;
		$data['agency'] = $this->agency ? $this->agency : null;

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
		  $this->contact_id = $zend_db->lastInsertId('mast_addr_assignment_contact','contact_id');
		}
	}

	//----------------------------------------------------------------
	// Generic Getters
	//----------------------------------------------------------------

	/**
	 * @return number
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
	public function __toString(){
	  return $this->getFirst_name().' '.$this->getLast_name();
	}
}