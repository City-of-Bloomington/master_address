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
    private $id;

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
	public function __construct($id=null)
	{
		if ($id) {
			if (is_array($id)) {
				$result = $id;
			}
			else {
				$zend_db = Database::getConnection();
				if(ctype_digit($id)){
				  $sql = 'select * from mast_street_name_type_master where id=?';
				}
				else{
				  $sql = 'select * from mast_street_name_type_master where street_name_type=?';
				}
				$result = $zend_db->fetchRow($sql,array($id));
			}

			if ($result) {
				foreach ($result as $field=>$value) {
					if ($value) {
						$this->$field = $value;
					}
				}
			}
			else {
				throw new Exception('streets/unknownStreetNameType');
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
	  if(!$this->street_name_type || !$this->description){
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
		$data['street_name_type'] = $this->street_name_type;
		if ($this->id) {
			$this->update($data);
		}
		else {
		    $this->insert($data);
		}
	}

	private function update($data)
	{
		$zend_db = Database::getConnection();
		$zend_db->update('mast_street_name_type_master',$data,"id={$this->id}");
	}

	private function insert($data)
	{
		$zend_db = Database::getConnection();
		$zend_db->insert('mast_street_name_type_master',$data);
		if (Database::getType()=='oracle') {
		    $this->id = $zend_db->lastSequenceId('street_name_type_id_s');
		}
		else {
		    $this->street_name_type = $zend_db->lastInsertId('mast_street_name_type_master','street_name_type');
		}

	}

	//----------------------------------------------------------------
	// Generic Getters
	//----------------------------------------------------------------
	/**
	 * @return string
	 */
	public function getId()
	{
		return $this->id;
	}

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

	/**
	 * @param string $string
	 */
	public function setStreet_name_type($string)
	{
	    $this->street_name_type = trim($string);
	}
	//----------------------------------------------------------------
	// Custom Functions
	// We recommend adding all your custom code down here at the bottom
	//----------------------------------------------------------------
	public function __toString()
	{
		return $this->getStreet_name_type();
	}

	/**
	 * alias for street_name_type
	 * @return string
	 */
	public function getType()
	{
		return $this->getStreet_name_type();
	}
}
