<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @author W. Sibo <sibow@bloomington.in.gov>
 */
class StreetName
{
	private $street_id;
	private $street_name;
	private $street_type_suffix_code;
	private $street_name_type;
	private $effective_start_date;
	private $effective_end_date;
	private $notes;
	private $street_direction_code;
	private $post_direction_suffix_code;
	private $id;


	private $direction;
	private $postDirection;
	private $streetNameType;
	private $street;
	private $streetType;


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
	 * @param int|array $id
	 */
	public function __construct($id=null)
	{
		if ($id) {
			if (is_array($id)) {
				$result = $id;
			}
			else {
				$zend_db = Database::getConnection();
				$sql = 'select * from mast_street_names where id=?';
				$result = $zend_db->fetchRow($sql,array($id));
			}

			if ($result) {
				foreach ($result as $field=>$value) {
					if ($value) {
					    if (preg_match('/date/',$field) && $value!='0000-00-00') {
							$value = new Date($value);
						}
						$this->$field = trim($value);
					}
				}
			}
			else {
				throw new Exception('streets/unknownMastStreetName');
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
		if (!$this->street_id || !$this->street_name) {
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
		$data['street_id'] = $this->street_id;
		$data['street_name'] = $this->street_name;
		$data['street_type_suffix_code'] = $this->street_type_suffix_code ? $this->street_type_suffix_code : null;
		$data['street_name_type'] = $this->street_name_type ? $this->street_name_type : null;
		$data['effective_start_date'] = $this->effective_start_date ? $this->effective_start_date->format('Y-m-d') : null;
		$data['effective_end_date'] = $this->effective_end_date ? $this->effective_end_date->format('Y-m-d') : null;
		$data['notes'] = $this->notes ? $this->notes : null;
		$data['street_direction_code'] = $this->street_direction_code ? $this->street_direction_code : null;
		$data['post_direction_suffix_code'] = $this->post_direction_suffix_code ? $this->post_direction_suffix_code : null;

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
		$zend_db->update('mast_street_names',$data,"id='{$this->id}'");
	}

	private function insert($data)
	{
		$zend_db = Database::getConnection();
		$zend_db->insert('mast_street_names',$data);
		if (Database::getType()=='oracle') {
			$this->id = $zend_db->lastSequenceId('street_names_id_s');
		}
		else {
		    $this->id = $zend_db->lastInsertId('mast_street_names','id');
		}
	}

	//----------------------------------------------------------------
	// Generic Getters
	//----------------------------------------------------------------
	/**
	 * @return number
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @return number
	 */
	public function getStreet_id()
	{
		return $this->street_id;
	}

	/**
	 * @return string
	 */
	public function getStreet_name()
	{
		return $this->street_name;
	}

	/**
	 * @return string
	 */
	public function getStreet_type_suffix_code()
	{
		return $this->street_type_suffix_code;
	}

	/**
	 * @return string
	 */
	public function getStreet_name_type()
	{
		return $this->street_name_type;
	}

	/**
	 * Returns the date/time in the desired format
	 * Format can be specified using either the strftime() or the date() syntax
	 *
	 * @param string $format
	 */
	public function getEffective_start_date($format=null)
	{
		if ($format && $this->effective_start_date) {
		    return $this->effective_start_date->format($format);
		}
		else {
			return $this->effective_start_date;
		}
	}

	/**
	 * Returns the date/time in the desired format
	 * Format can be specified using either the strftime() or the date() syntax
	 *
	 * @param string $format
	 */
	public function getEffective_end_date($format=null)
	{
		if ($format && $this->effective_end_date) {
		  return $this->effective_end_date->format($format);
		}
		else {
			return $this->effective_end_date;
		}
	}

	/**
	 * @return string
	 */
	public function getNotes()
	{
		return $this->notes;
	}

	/**
	 * @return char
	 */
	public function getStreet_direction_code()
	{
		return $this->street_direction_code;
	}

	/**
	 * @return char
	 */
	public function getPost_direction_suffix_code()
	{
		return $this->post_direction_suffix_code;
	}


	/**
	 * @return Street
	 */
	public function getStreet()
	{
		if ($this->street_id) {
			if (!$this->street) {
				$this->street = new Street($this->street_id);
			}
			return $this->street;
		}
		return null;
	}

	/**
	 * @return Direction
	 */
	public function getDirection()
	{
		if ($this->street_direction_code) {
			if (!$this->direction) {
				$this->direction = new Direction($this->street_direction_code);
			}
			return $this->direction;
		}
		return new Direction();
	}

	/**
	 * @return Direction
	 */
	public function getPostDirection()
	{
		if ($this->post_direction_suffix_code) {
			if (!$this->postDirection) {
				$this->postDirection = new Direction($this->post_direction_suffix_code);
			}
			return $this->postDirection;
		}
		return new Direction();
	}

	/**
	 * @return StreetType
	 */
	public function getStreetType()
	{
		if ($this->street_type_suffix_code) {
			if (!$this->streetType) {
				$this->streetType = new StreetType($this->street_type_suffix_code);
			}
			return $this->streetType;
		}
		return new StreetType();
	}

	/**
	 * @return StreetNameType
	 */
	public function getStreetNameType()
	{
		if ($this->street_name_type) {
			if (!$this->streetNameType) {
				$this->streetNameType = new StreetNameType($this->street_name_type);
			}
			return $this->streetNameType;
		}
		return new StreetNameType();
	}
	//----------------------------------------------------------------
	// Generic Setters
	//----------------------------------------------------------------

	/**
	 * @param number $number
	 */
	public function setStreet_id($number)
	{
		$this->street_id = $number;
	}

	/**
	 * @param string $string
	 */
	public function setStreet_name($string)
	{
		$this->street_name = trim($string);
	}

	/**
	 * @param string $string
	 */
	public function setStreet_type_suffix_code($string)
	{
		$this->streetType = new StreetType($string);
		$this->street_type_suffix_code = $this->streetType->getCode();
	}

	/**
	 * @param string $string
	 */
	public function setStreet_name_type($string)
	{
		$this->streetNameType = new StreetNameType($string);
		$this->street_name_type = $this->streetNameType->getType();
	}

	/**
	 * Sets the date
	 *
	 * Date arrays should match arrays produced by getdate()
	 *
	 * Date string formats should be in something strtotime() understands
	 * http://www.php.net/manual/en/function.strtotime.php
	 *
	 * @param int|string|array $date
	 */
	public function setEffective_start_date($date)
	{
		if ($date) {
			$this->effective_start_date = new Date($date);
		}
		else {
			$this->effective_start_date = null;
		}
	}

	/**
	 * Sets the date
	 *
	 * Date arrays should match arrays produced by getdate()
	 *
	 * Date string formats should be in something strtotime() understands
	 * http://www.php.net/manual/en/function.strtotime.php
	 *
	 * @param int|string|array $date
	 */
	public function setEffective_end_date($date)
	{
		if ($date) {
			$this->effective_end_date = new Date($date);
		}
		else {
			$this->effective_end_date = null;
		}
	}

	/**
	 * @param string $string
	 */
	public function setNotes($string)
	{
		$this->notes = trim($string);
	}

	/**
	 * @param char $char
	 */
	public function setStreet_direction_code($char)
	{
		$this->direction = new Direction($char);
		$this->street_direction_code = $this->direction->getCode();
	}

	/**
	 * @param char $char
	 */
	public function setPost_direction_suffix_code($char)
	{
		$this->postDirection = new Direction($char);
		$this->post_direction_suffix_code = $this->postDirection->getCode();
	}

	/**
	 * @param Street $street
	 */
	public function setStreet($street)
	{
		$this->street_id = $street->getId();
		$this->street = $street;
	}

	/**
	 * @param Direction $direction
	 */
	public function setDirection($direction)
	{
		$this->street_direction_code = $direction->getCode();
		$this->direction = $direction;
	}

	/**
	 * @param Direction $postDirection
	 */
	public function setPostDirection($direction)
	{
		$this->post_direction_suffix_code = $direction->getCode();
		$this->postDirection = $direction;
	}

	/**
	 * @param Direction $direction
	 */
	public function setStreetNameType($type)
	{
		$this->street_name_type = $type->getId();
		$this->streetNameType = $type;
	}

	/**
	 * @param Direction $streetType
	 */
	public function setStreetType($streetType)
	{
		$this->street_type_suffix_code = $streetType->getCode();
		$this->streetType = $streetType;
	}
	//----------------------------------------------------------------
	// Custom Functions
	// We recommend adding all your custom code down here at the bottom
	//----------------------------------------------------------------
	public function __toString()
	{
		$name = $this->getDirectionCode() ? $this->getDirectionCode().' ' : '';
		$name.= $this->getName();
		$name.= $this->getPostDirectionCode() ? ' '.$this->getPostDirectionCode() : '';
		$name.= ' '.$this->getStreet_type_suffix_code();
		return $name;
	}
	/**
	 * Alias for getStreet_name()
	 *
	 * @return string
	 */
	public function getName()
	{
		return $this->getStreet_name();
	}

	/**
	 * Alias for getStreet_direction_code()
	 *
	 * @return string
	 */
	public function getDirectionCode()
	{
		return $this->getStreet_direction_code();
	}

	/**
	 * Alias for getPost_direction_suffix_code()
	 *
	 * @return string
	 */
	public function getPostDirectionCode()
	{
		return $this->getPost_direction_suffix_code();
	}
}
