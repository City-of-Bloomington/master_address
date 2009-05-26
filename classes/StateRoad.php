<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
class StateRoad
{
	private $state_road_id;
	private $description;
	private $abbreviation;

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
	 * @param int|array $state_road_id
	 */
	public function __construct($state_road_id=null)
	{
		if ($state_road_id) {
			if (is_array($state_road_id)) {
				$result = $state_road_id;
			}
			else {
				$zend_db = Database::getConnection();
				$sql = 'select * from state_road_master where state_road_id=?';
				$result = $zend_db->fetchRow($sql,array($state_road_id));
			}

			if ($result) {
				foreach ($result as $field=>$value) {
					if ($value) {
						$this->$field = $value;
					}
				}
			}
			else {
				throw new Exception('state_roads/unknownStateRoad');
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
		if (!$this->description || !$this->abbreviation) {
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
		$data['abbreviation'] = $this->abbreviation;

		if ($this->state_road_id) {
			$this->update($data);
		}
		else {
			$this->insert($data);
		}
	}

	private function update($data)
	{
		$zend_db = Database::getConnection();
		$zend_db->update('state_road_master',$data,"state_road_id='{$this->state_road_id}'");
	}

	private function insert($data)
	{
		$zend_db = Database::getConnection();
		$zend_db->insert('state_road_master',$data);
		if (Database::getType()=='oracle') {
			$this->state_road_id = $zend_db->lastSequenceId('state_road_id_s');
		}
		else {
			$this->state_road_id = $zend_db->lastInsertId();
		}
	}

	//----------------------------------------------------------------
	// Generic Getters
	//----------------------------------------------------------------
	/**
	 * Alias for getState_road_id
	 *
	 * @return int
	 */
	public function getId()
	{
		return $this->getState_road_id();
	}

	/**
	 * @return int
	 */
	public function getState_road_id()
	{
		return $this->state_road_id;
	}

	/**
	 * @return string
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * @return string
	 */
	public function getAbbreviation()
	{
		return $this->abbreviation;
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
	public function setAbbreviation($string)
	{
		$this->abbreviation = trim($string);
	}


	//----------------------------------------------------------------
	// Custom Functions
	// We recommend adding all your custom code down here at the bottom
	//----------------------------------------------------------------
}
