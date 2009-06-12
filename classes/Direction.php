<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
class Direction
{
	private $direction_code;
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
	 * @param int|array $direction_code
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
				    $sql = 'select * from mast_street_direction_master where id=?';
				}
				else{
				    $sql = 'select * from mast_street_direction_master where direction_code = ?';
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
				throw new Exception('streets/unknownDirection');
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
	  if (!$this->description || !$this->direction_code) {
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
		$data['direction_code'] = $this->direction_code;
		$data['description'] = $this->description;

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
		$zend_db->update('mast_street_direction_master',$data,"id='{$this->id}'");
	}

	private function insert($data)
	{
	    $zend_db = Database::getConnection();
		$zend_db->insert('mast_street_direction_master',$data);
		if (Database::getType()=='oracle') {
		    $this->id = $zend_db->lastSequenceId('direction_id_seq');
		}
		else {
		    $this->id = $zend_db->lastInsertId('mast_street_direction_master','id');
		}
	}

	//----------------------------------------------------------------
	// Generic Getters
	//----------------------------------------------------------------
	/**
	 * Alias for getDirection_code()
	 *
	 * return $string
	 */
	public function getCode()
	{
		return $this->getDirection_code();
	}

	/**
	 * return $string
	 */
	public function getId()
	{
		return $this->id;
	}
	/**
	 * @return string
	 */
	public function getDirection_code()
	{
		return $this->direction_code;
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
	public function setDirection_code($string)
	{
		$this->direction_code = trim($string);
	}
	/**
	 * @param string $string
	 */
	public function setDescription($string)
	{
		$this->description = trim($string);
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
	  return ($this->description)?$this->description:"";
	}
	//----------------------------------------------------------------
	// Custom Functions
	// We recommend adding all your custom code down here at the bottom
	//----------------------------------------------------------------
}
