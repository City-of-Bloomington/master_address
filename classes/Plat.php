<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
class Plat
{
	private $plat_id;
	private $name;
	private $township_id;
	private $effective_start_date;
	private $effective_end_date;
	private $plat_type;
	private $plat_cabinet;
	private $envelope;
	private $notes;

	private $township;

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
	 * @param int|array $plat_id
	 */
	public function __construct($plat_id=null)
	{
		if ($plat_id) {
			if (is_array($plat_id)) {
				$result = $plat_id;
			}
			else {
				$zend_db = Database::getConnection();
				$sql = 'select * from plat_master where plat_id=?';
				$result = $zend_db->fetchRow($sql,array($plat_id));
			}

			if ($result) {
				foreach ($result as $field=>$value) {
					if ($value) {
						// Convert the date fields into timestamps
						if (($field=='effective_start_date' || $field=='effective_end_date')
							&& $value != '0000-00-00') {
							$value = strtotime($value);
						}

						$this->$field = $value;
					}
				}
			}
			else {
				throw new Exception('plat_master/unknownPlatMaster');
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
		if (!$this->name || !$this->plat_type || !$this->township_id) {
			throw new Exception('missingRequriedFields');
		}

	}

	/**
	 * Saves this record back to the database
	 */
	public function save()
	{
		$this->validate();

		$data = array();
		$data['name'] = $this->name;
		$data['township_id'] = $this->township_id;
		$data['effective_start_date'] = $this->effective_start_date ? date('Y-m-d',$this->effective_start_date) : null;
		$data['effective_end_date'] = $this->effective_end_date ? date('Y-m-d',$this->effective_end_date) : null;
		$data['plat_type'] = $this->plat_type;
		$data['plat_cabinet'] = $this->plat_cabinet ? $this->plat_cabinet : null;
		$data['envelope'] = $this->envelope ? $this->envelope : null;
		$data['notes'] = $this->notes ? $this->notes : null;

		if ($this->plat_id) {
			$this->update($data);
		}
		else {
			$this->insert($data);
		}
	}

	private function update($data)
	{
		$zend_db = Database::getConnection();
		$zend_db->update('plat_master',$data,"plat_id='{$this->plat_id}'");
	}

	private function insert($data)
	{
		$zend_db = Database::getConnection();
		$zend_db->insert('plat_master',$data);
		if (Database::getType()=='oracle') {
			$this->plat_id = $zend_db->lastSequenceId('plat_id_s');
		}
		else {
			$this->plat_id = $zend_db->lastInsertId();
		}
	}
	//----------------------------------------------------------------
	// Generic Getters
	//----------------------------------------------------------------
	
	/**
	 * @return number
	 */
	public function getPlat_id()
	{
		return $this->plat_id;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @return number
	 */
	public function getTownship_id()
	{
		return $this->township_id;
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
			if (strpos($format,'%')!==false) {
				return strftime($format,$this->effective_start_date);
			}
			else {
				return date($format,$this->effective_start_date);
			}
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
			if (strpos($format,'%')!==false) {
				return strftime($format,$this->effective_end_date);
			}
			else {
				return date($format,$this->effective_end_date);
			}
		}
		else {
			return $this->effective_end_date;
		}
	}

	/**
	 * @return char
	 */
	public function getPlat_type()
	{
		return $this->plat_type;
	}

	/**
	 * @return string
	 */
	public function getPlat_cabinet()
	{
		return $this->plat_cabinet;
	}

	/**
	 * @return string
	 */
	public function getEnvelope()
	{
		return $this->envelope;
	}

	/**
	 * @return string
	 */
	public function getNotes()
	{
		return $this->notes;
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

	//----------------------------------------------------------------
	// Generic Setters
	//----------------------------------------------------------------

	/**
	 * @param string $string
	 */
	public function setName($string)
	{
		$this->name = trim($string);
	}

	/**
	 * @param number $number
	 */
	public function setTownship_id($number)
	{
		$this->township_id = $number;
	}

	/**
	 * Sets the date
	 *
	 * Dates and times should be stored as timestamps internally.
	 * This accepts dates and times in multiple formats and sets the internal timestamp
	 * Accepted formats are:
	 * 		array - in the form of PHP getdate()
	 *		timestamp
	 *		string - anything strtotime understands
	 * @param date $date
	 */
	public function setEffective_start_date($date)
	{
		if (is_array($date)) {
			$this->effective_start_date = $this->dateArrayToTimestamp($date);
		}
		elseif (ctype_digit($date)) {
			$this->effective_start_date = $date;
		}
		else {
			$this->effective_start_date = strtotime($date);
		}
	}

	/**
	 * Sets the date
	 *
	 * Dates and times should be stored as timestamps internally.
	 * This accepts dates and times in multiple formats and sets the internal timestamp
	 * Accepted formats are:
	 * 		array - in the form of PHP getdate()
	 *		timestamp
	 *		string - anything strtotime understands
	 * @param date $date
	 */
	public function setEffective_end_date($date)
	{
		if (is_array($date)) {
			$this->effective_end_date = $this->dateArrayToTimestamp($date);
		}
		elseif (ctype_digit($date)) {
			$this->effective_end_date = $date;
		}
		else {
			$this->effective_end_date = strtotime($date);
		}
	}

	/**
	 * @param char $char
	 */
	public function setPlat_type($char)
	{
		$this->plat_type = $char;
	}

	/**
	 * @param string $string
	 */
	public function setPlat_cabinet($string)
	{
		$this->plat_cabinet = trim($string);
	}

	/**
	 * @param string $string
	 */
	public function setEnvelope($string)
	{
		$this->envelope = trim($string);
	}

	/**
	 * @param string $string
	 */
	public function setNotes($string)
	{
		$this->notes = trim($string);
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
	/**
	 * Alias for getPlat_id()
	 *
	 * @return int
	 */
	public function getId()
	{
		return $this->getPlat_id();
	}

	/**
	 * Returns an array of the valid plat types for the system
	 *
	 * @return array
	 */
	public static function getPlat_types()
	{
		return array('A','C','S');
	}

	/**
	 * Alias for getPlat_type()
	 *
	 * @return string
	 */
	public function getType()
	{
		return $this->getPlat_type();
	}

	/**
	 * Alias for getPlat_cabinet()
	 *
	 * @return string
	 */
	public function getCabinet()
	{
		return $this->getPlat_cabinet();
	}
}
