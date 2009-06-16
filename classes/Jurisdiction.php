<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
class Jurisdiction
{
	private $gov_jur_id;
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
	 * @param int|array $gov_jur_id
	 */
	public function __construct($gov_jur_id=null)
	{
		if ($gov_jur_id) {
			if (is_array($gov_jur_id)) {
				$result = $gov_jur_id;
			}
			else {
				$zend_db = Database::getConnection();
				$sql = 'select * from governmental_jurisdiction_mast where gov_jur_id=?';
				$result = $zend_db->fetchRow($sql,array($gov_jur_id));
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

		if ($this->gov_jur_id) {
			$this->update($data);
		}
		else {
			$this->insert($data);
		}
	}

	private function update($data)
	{
		$zend_db = Database::getConnection();
		$zend_db->update('governmental_jurisdiction_mast',$data,"gov_jur_id='{$this->gov_jur_id}'");
	}

	private function insert($data)
	{
		$zend_db = Database::getConnection();
		$zend_db->insert('governmental_jurisdiction_mast',$data);
		$this->gov_jur_id = $zend_db->lastInsertId('governmental_jurisdiction_mast','gov_jur_id');
	}

	//----------------------------------------------------------------
	// Generic Getters
	//----------------------------------------------------------------
	/**
	 * Alias for getGov_jur_id()
	 *
	 * @return int
	 */
	public function getId()
	{
		return $this->getGov_jur_id();
	}

	/**
	 * @return number
	 */
	public function getGov_jur_id()
	{
		return $this->gov_jur_id;
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
