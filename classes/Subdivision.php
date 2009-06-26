<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
class Subdivision
{
	private $subdivision_id;
	private $township_id;

	private $township;
	private $subdivisionNameList;
	private $streetList;
	private $addressList;
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
	 * @param int|array $subdivision_id
	 */
	public function __construct($subdivision_id=null)
	{
		if ($subdivision_id) {
			if (is_array($subdivision_id)) {
				$result = $subdivision_id;
			}
			else {
				$zend_db = Database::getConnection();
				$sql = 'select * from subdivision_master where subdivision_id=?';
				$result = $zend_db->fetchRow($sql,array($subdivision_id));
			}

			if ($result) {
				foreach ($result as $field=>$value) {
					if ($value) {
						$this->$field = $value;
					}
				}
			}
			else {
				throw new Exception('subdivision_master/unknownSubdivisionMaster');
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
		// Check for required fields here.
	    // Throw an exception if anything is missing.
	}

	/**
	 * Saves this record back to the database
	 */
	public function save()
	{
		$this->validate();

		$data = array();
		$data['township_id'] = $this->township_id;

		if ($this->subdivision_id) {
			$this->update($data);
		}
		else {
			$this->insert($data);
		}
	}

	private function update($data)
	{
		$zend_db = Database::getConnection();
		$zend_db->update('subdivision_master',$data,"subdivision_id='{$this->subdivision_id}'");
	}

	private function insert($data)
	{
		$zend_db = Database::getConnection();
		$zend_db->insert('subdivision_master',$data);
		if (Database::getType()=='oracle') {
			$this->subdivision_id = $zend_db->lastSequenceId('subdivision_id_s');
		}
		else {
		    $this->subdivision_id = $zend_db->lastInsertId('subdivision_master','subdivision_id');
		}
	}

	//----------------------------------------------------------------
	// Generic Getters
	//----------------------------------------------------------------

	/**
	 * @return number
	 */
	public function getSubdivision_id()
	{
		return $this->subdivision_id;
	}

	/**
	 * alias for subdivision_id
	 * @return number
	 */
	public function getId()
	{
		return $this->subdivision_id;
	}
	
	/**
	 * @return number
	 */
	public function getTownship_id()
	{
		return $this->township_id;
	}

	/**
	 * @return Township
	 */
	public function getTownship()
	{
		if ($this->township_id) {
			if (!$this->township) {
				$this->township = new Township($this->township_id);
			}
			return $this->township;
		}
		return null;
	}
	/**
	 * @return SubdivisionNameList
	 */
	public function getSubdivisionNameList()
	{
		if ($this->subdivision_id) {
			if (!$this->subdivisionNameList) {
			    $subdivisionNameList = new SubdivisionNameList(array('subdivision_id'=>$this->subdivision_id));	  
				$this->subdivisionNameList = $subdivisionNameList;
			}
			return $this->subdivisionNameList;
		}
		return null;
	}
	
	public function getAddressList()
	{
		if ($this->subdivision_id) {
			if (!$this->addressList) {
			    $addressList = new AddressList();
				$addressList->search(array('subdivision_id'=>$this->subdivision_id));
				$this->addressList = $addressList;
			}
			return $this->addressList;
		}
		return null;
	}
	
	public function getStreetList()
	{
		if ($this->subdivision_id) {
			if (!$this->streetList) {
			    $streetList = new StreetList();
				$streetList->search(array('subdivision_id'=>$this->subdivision_id));
				$this->streetList = $streetList;
			}
			return $this->streetList;
		}
		return null;
	}
	//----------------------------------------------------------------
	// Generic Setters
	//----------------------------------------------------------------

	/**
	 * @param number $number
	 */
	public function setTownship_id($number)
	{
		$this->township_id = $number;
	}


	/**
	 * @param Township $township
	 */
	public function setTownship($township)
	{
		$this->township_id = $township->getId();
		$this->township = $township;
	}


	//----------------------------------------------------------------
	// Custom Functions
	// We recommend adding all your custom code down here at the bottom
	//----------------------------------------------------------------
	public function __toString()
	{
	    return $this->subdivision_id;
	}
	
}
