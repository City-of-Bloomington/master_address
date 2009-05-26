<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
class Jurisdiction
{
	private $jurisdiction_id;
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
	 * @param int|array $jurisdiction_id
	 */
	public function __construct($jurisdiction_id=null)
	{
		if ($jurisdiction_id) {
			if (is_array($jurisdiction_id)) {
				$result = $jurisdiction_id;
			}
			else {
				$zend_db = Database::getConnection();
				$sql = 'select * from addr_jurisdiction_master where jurisdiction_id=?';
				$result = $zend_db->fetchRow($sql,array($jurisdiction_id));
			}

			if ($result) {
				foreach ($result as $field=>$value) {
					if ($value) {
						$this->$field = $value;
					}
				}
			}
			else {
				throw new Exception('jurisdictions/unknownJurisdiction');
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

		if ($this->jurisdiction_id) {
			$this->update($data);
		}
		else {
			$this->insert($data);
		}
	}

	private function update($data)
	{
		$zend_db = Database::getConnection();
		$zend_db->update('addr_jurisdiction_master',$data,"jurisdiction_id='{$this->jurisdiction_id}'");
	}

	private function insert($data)
	{
		$zend_db = Database::getConnection();
		$zend_db->insert('addr_jurisdiction_master',$data);
		if (Database::getType()=='oracle') {
			$this->jurisdiction_id = $zend_db->lastSequenceId('jurisdiction_id_s');
		}
		else {
			$this->jurisdiction_id = $zend_db->lastInsertId();
		}

	}

	//----------------------------------------------------------------
	// Generic Getters
	//----------------------------------------------------------------
	/**
	 * Alias of getJurisdiction_id()
	 *
	 * @return int
	 */
	public function getId()
	{
		return $this->getJurisdiction_id();
	}

	/**
	 * @return number
	 */
	public function getJurisdiction_id()
	{
		return $this->jurisdiction_id;
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
		return $this->description;
	}
}
