<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
class StreetNameType
{
	private $street_name_type;
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
	 * @param int|array $street_name_type
	 */
	public function __construct($street_name_type=null)
	{
		if ($street_name_type) {
			if (is_array($street_name_type)) {
				$result = $street_name_type;
			}
			else {
				$zend_db = Database::getConnection();
				$sql = 'select * from mast_street_name_type_master where street_name_type=?';
				$result = $zend_db->fetchRow($sql,array($street_name_type));
			}

			if ($result) {
				foreach ($result as $field=>$value) {
					if ($value) {
						$this->$field = $value;
					}
				}
			}
			else {
				throw new Exception('mast_street_name_type_master/unknownMastStreetNameTypeMaster');
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
		$data['description'] = $this->description ? $this->description : null;

		if ($this->street_name_type) {
			$this->update($data);
		}
		else {
		    // no insert
		    //$this->insert($data);
		}
	}

	private function update($data)
	{
		$zend_db = Database::getConnection();
		$zend_db->update('mast_street_name_type_master',$data,"street_name_type='{$this->street_name_type}'");
	}

	private function insert($data)
	{
		$zend_db = Database::getConnection();
		$zend_db->insert('mast_street_name_type_master',$data);
		$this->street_name_type = $zend_db->lastInsertId('mast_street_name_type_master','street_name_type');
	}

	//----------------------------------------------------------------
	// Generic Getters
	//----------------------------------------------------------------

	/**
	 * @return string
	 */
	public function getStreet_name_type()
	{
		return $this->street_name_type;
	}
	
	/**
	 * @return string
	 */
	public function getId()
	{
		return $this->street_name_type;
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
		return $this->street_name_type;
	}
}
