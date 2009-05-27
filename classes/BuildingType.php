<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
class BuildingType
{
	private $building_type_id;
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
	public function __construct($building_type_id=null)
	{
		if ($building_type_id) {
			if (is_array($building_type_id)) {
				$result = $building_type_id;
			}
			else {
				$zend_db = Database::getConnection();
				$sql = 'select * from building_types_master where building_type_id=?';
				$result = $zend_db->fetchRow($sql,array($building_type_id));
			}

			if ($result) {
				foreach ($result as $field=>$value) {
					if ($value) {
						$this->$field = $value;
					}
				}
			}
			else {
				throw new Exception('buildingTypes/unknownBuildingType');
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

		if ($this->building_type_id) {
			$this->update($data);
		}
		else {
			$this->insert($data);
		}
	}

	private function update($data)
	{
		$zend_db = Database::getConnection();
		$zend_db->update('building_types_master',$data,"building_type_id='{$this->building_type_id}'");
	}

	private function insert($data)
	{
		$zend_db = Database::getConnection();
		$zend_db->insert('building_types_master',$data);
		$this->building_type_id = $zend_db->lastInsertId('building_types_master','building_type_id');
	}

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
		return $this->getBuilding_type_id();
	}

	/**
	 * @return int
	 */
	public function getBuilding_type_id()
	{
		return $this->building_type_id;
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
