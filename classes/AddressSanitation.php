<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
class AddressSanitation
{
	private $street_address_id;
	private $trash_pickup_day;
	private $recycle_week;
	private $large_item_pickup_day;

	private $streetAddress; // the obj does not exist yet

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
	 * @param int|array $street_address_id
	 */
	public function __construct($street_address_id=null)
	{
		if ($street_address_id) {
			if (is_array($street_address_id)) {
				$result = $street_address_id;
			}
			else {
				$zend_db = Database::getConnection();
				$sql = 'select * from mast_address_sanitation where street_address_id=?';
				$result = $zend_db->fetchRow($sql,array($street_address_id));
			}

			if ($result) {
				foreach ($result as $field=>$value) {
					if ($value) {
						$this->$field = $value;
					}
				}
			}
			else {
				throw new Exception('mast_address_sanitation/unknownMastAddressSanitation');
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
	public function isNew()
	{
		if ($this->street_address_id) {
		  $zend_db = Database::getConnection();
		  $sql = 'select * from mast_address_sanitation where street_address_id=?';
		  $result = $zend_db->fetchRow($sql,$this->street_address_id);
		  if ($result) {
			return false;
		  }
		  else {
			return true;
		  }
		}
		else {
		    throw new Exception('addresses/unsetField');
		}
	}
	/**
	 * Saves this record back to the database
	 */
	public function save()
	{
		$this->validate();

		$data = array();
		$data['trash_pickup_day'] = $this->trash_pickup_day ? $this->trash_pickup_day : null;
		$data['recycle_week'] = $this->recycle_week ? $this->recycle_week : null;
		$data['large_item_pickup_day'] = $this->large_item_pickup_day ? $this->large_item_pickup_day : null;

		if (!$this->isNew()) {
			$this->update($data);
		}
		else {
			$this->insert($data);
		}
	}

	private function update($data)
	{
		$zend_db = Database::getConnection();
		$zend_db->update('mast_address_sanitation',$data,"street_address_id='{$this->street_address_id}'");
	}

	private function insert($data)
	{
		$zend_db = Database::getConnection();
		$zend_db->insert('mast_address_sanitation',$data,"street_address_id='$this->street_address_id)'");
		//
		// it is a foreign key, must be set before
		// so we do not need the following
		//
		// $this->street_address_id = $zend_db->lastInsertId('mast_address_sanitation','street_address_id');
	}

	//----------------------------------------------------------------
	// Generic Getters
	//----------------------------------------------------------------

	/**
	 * @return number
	 */
	public function getStreet_address_id()
	{
		return $this->street_address_id;
	}

	/**
	 * @return number
	 */
	public function getId()
	{
		return $this->street_address_id;
	}
	/**
	 * @return string
	 */
	public function getTrash_pickup_day()
	{
		return $this->trash_pickup_day;
	}

	/**
	 * @return string
	 */
	public function getRecycle_week()
	{
		return $this->recycle_week;
	}

	/**
	 * @return string
	 */
	public function getLarge_item_pickup_day()
	{
		return $this->large_item_pickup_day;
	}

	/**
	 * @return Street_address
	 */
	public function getStreetAddress()
	{
		if ($this->street_address_id) {
			if (!$this->streetAddress) {
				$this->streetAddress = new StreetAddress($this->street_address_id);
			}
			return $this->streetAddress;
		}
		return null;
	}

	//----------------------------------------------------------------
	// Generic Setters
	//----------------------------------------------------------------

	/**
	 * @param string $string
	 */
	public function setTrash_pickup_day($string)
	{
		$this->trash_pickup_day = trim($string);
	}

	/**
	 * @param string $string
	 */
	public function setRecycle_week($string)
	{
		$this->recycle_week = trim($string);
	}

	/**
	 * @param string $string
	 */
	public function setLarge_item_pickup_day($string)
	{
		$this->large_item_pickup_day = trim($string);
	}

	/**
	 * @param Street_address $street_address
	 */
	public function setStreetAddress($streetAddress)
	{
		$this->street_address_id = $streetAddress->getId();
		$this->streetAddress = $streetAddress;
	}

	//----------------------------------------------------------------
	// Custom Functions
	// We recommend adding all your custom code down here at the bottom
	//----------------------------------------------------------------

}
