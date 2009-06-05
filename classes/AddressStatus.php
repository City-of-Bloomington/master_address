<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
class AddressStatus
{
	private $status_code;
	private $description;

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
	 * @param int|array $status_code
	 */
	public function __construct($status_code=null)
	{
		if ($status_code) {
			if (is_array($status_code)) {
				$result = $status_code;
			}
			else {
				$zend_db = Database::getConnection();
				$sql = 'select * from mast_address_status_lookup where status_code=?';
				$result = $zend_db->fetchRow($sql,array($status_code));
			}

			if ($result) {
				foreach ($result as $field=>$value) {
					if ($value) {
						$this->$field = $value;
					}
				}
			}
			else {
				throw new Exception('addresses/unknownAddressStatus');
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
		if (!$this->description) {
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
		$data['description'] = $this->description;

		if ($this->status_code) {
			$this->update($data);
		}
		else {
			$this->insert($data);
		}
	}

	private function update($data)
	{
		$zend_db = Database::getConnection();
		$zend_db->update('mast_address_status_lookup',$data,"status_code='{$this->status_code}'");
	}

	private function insert($data)
	{
		$zend_db = Database::getConnection();
		$zend_db->insert('mast_address_status_lookup',$data);
		if (Database::getType()=='oracle') {
			$this->status_code = $zend_db->lastSequenceId('address_status_code_seq');
		}
		else {
			$this->status_code = $zend_db->lastInsertId();
		}
	}

	//----------------------------------------------------------------
	// Generic Getters
	//----------------------------------------------------------------
	/**
	 * Alias for getStatus_code()
	 *
	 * @return int
	 */
	public function getCode()
	{
		return $this->getStatus_code();
	}
	
	/**
	 * Alias for getStatus_code()
	 *
	 * @return int
	 */
	public function getId()
	{
		return $this->getStatus_code();
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
	public function getDescription()
	{
		return $this->description;
	}

	//----------------------------------------------------------------
	// Generic Setters
	//----------------------------------------------------------------

	/**
	 * @param string $string
	 */
	public function setDescription($string)
	{
		$this->description = trim($string);
	}


	//----------------------------------------------------------------
	// Custom Functions
	// We recommend adding all your custom code down here at the bottom
	//----------------------------------------------------------------
	public function __toString()
	{
		return $this->getDescription();
	}
}
