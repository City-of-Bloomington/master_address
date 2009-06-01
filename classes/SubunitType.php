<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
class SubunitType
{
	private $sudtype;
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
	 * @param int|array $building_type_id
	 */
	public function __construct($sudtype=null)
	{
		if ($sudtype) {
			if (is_array($sudtype)) {
				$result = $sudtype;
			}
			else {
				$zend_db = Database::getConnection();
				$sql = 'select * from mast_addr_subunit_types_mast where sudtype=?';
				$result = $zend_db->fetchRow($sql,array($sudtype));
			}

			if ($result) {
				foreach ($result as $field=>$value) {
					if ($value) {
						$this->$field = $value;
					}
				}
			}
			else {
				throw new Exception('buildings/unknownSubunitType');
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

		if ($this->sudtype) {
			$this->update($data);
		}
		else {
		  // no insert
		  // $this->insert($data);
		}
	}

	private function update($data)
	{
		$zend_db = Database::getConnection();
		$zend_db->update('mast_addr_subunit_types_mast',$data,"sudtype='{$this->sudtype}'");
	}
	/*
	private function insert($data)
	{
		$zend_db = Database::getConnection();
		$zend_db->insert('building_types_master',$data);
		if (Database::getType()=='oracle') {
			$this->building_type_id = $zend_db->lastSequenceId('building_type_id_s');
		}
		$this->building_type_id = $zend_db->lastInsertId();
	}
	*/
	//----------------------------------------------------------------
	// Generic Getters
	//----------------------------------------------------------------
	/**
	 * Alias of getBuilding_type_id()
	 *
	 * @return int
	 */
	public function getId()
	{
		return $this->getSudtype();
	}

	/**
	 * @return int
	 */
	public function getSudtype()
	{
		return $this->sudtype;
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
