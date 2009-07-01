<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
class SubdivisionName
{
	private $subdivision_id;
	private $subdivision_name_id;
	private $name;
	private $phase;
	private $status;
	private $effective_start_date;
	private $effective_end_date;


	private $subdivision;

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
	 * @param int|array $subdivision_name_id
	 */
	public function __construct($subdivision_name_id=null)
	{
		if ($subdivision_name_id) {
			if (is_array($subdivision_name_id)) {
				$result = $subdivision_name_id;
			}
			else {
				$zend_db = Database::getConnection();
				$sql = 'select * from subdivision_names where subdivision_name_id=?';
				$result = $zend_db->fetchRow($sql,array($subdivision_name_id));
			}

			if ($result) {
				foreach ($result as $field=>$value) {
					if ($value) {
					  if (($field=='effective_start_date' || $field=='effective_end_date')
							&& $value != '0000-00-00') {
							$value = new Date($value);
						}
						$this->$field = $value;
					}
				}
			}
			else {
				throw new Exception('subdivision_names/unknownSubdivisionName');
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
		$data['subdivision_id'] = $this->subdivision_id ? $this->subdivision_id : null;
		$data['name'] = $this->name ? $this->name : null;
		$data['phase'] = $this->phase ? $this->phase : null;
		$data['status'] = $this->status ? $this->status : null;
		$data['effective_start_date'] = $this->effective_start_date ? $this->effective_start_date->format('Y-n-j') : null;
		$data['effective_end_date'] = $this->effective_end_date ? $this->effective_end_date->format('Y-n-j') : null;

		if ($this->subdivision_name_id) {
			$this->update($data);
		}
		else {
			$this->insert($data);
		}
	}

	private function update($data)
	{
		$zend_db = Database::getConnection();
		$zend_db->update('subdivision_names',$data,"subdivision_name_id='{$this->subdivision_name_id}'");
	}

	private function insert($data)
	{
		$zend_db = Database::getConnection();
		$zend_db->insert('subdivision_names',$data);
		if (Database::getType()=='oracle') {
			$this->subdivision_name_id = $zend_db->lastSequenceId('subdivision_name_id_s');
		}
		else {
		  $this->subdivision_name_id = $zend_db->lastInsertId('subdivision_names','subdivision_name_id');
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
	 * @return number
	 */
	public function getSubdivision_name_id()
	{
		return $this->subdivision_name_id;
	}
	
	/**
	 * an alias for subdivision_name_id
	 * @return number
	 */
	public function getId()
	{
		return $this->subdivision_name_id;
	}
	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getPhase()
	{
		return $this->phase;
	}

	/**
	 * @return string
	 */
	public function getStatus()
	{
		return $this->status;
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
	 * @return Subdivision
	 */
	public function getSubdivision()
	{
		if ($this->subdivision_id) {
			if (!$this->subdivision) {
				$this->subdivision = new Subdivision($this->subdivision_id);
			}
			return $this->subdivision;
		}
		return null;
	}

	/**
	 * @return array
	 */
	public function getStatuses()
	{
	  return array("CURRENT","RENAMED");
	}
	//----------------------------------------------------------------
	// Generic Setters
	//----------------------------------------------------------------

	/**
	 * @param number $number
	 */
	public function setSubdivision_id($number)
	{
		$this->subdivision_id = $number;
	}

	/**
	 * @param string $string
	 */
	public function setName($string)
	{
		$this->name = trim($string);
	}

	/**
	 * @param string $string
	 */
	public function setPhase($string)
	{
		$this->phase = trim($string);
	}

	/**
	 * @param string $string
	 */
	public function setStatus($string)
	{
		$this->status = trim($string);
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
		if ($date) {
			$this->effective_end_date = new Date($date);
		}
		else {
			$this->effective_end_date = null;
		}
	}

	/**
	 * @param Subdivision $subdivision
	 */
	public function setSubdivision($subdivision)
	{
		$this->subdivision_id = $subdivision->getId();
		$this->subdivision = $subdivision;
	}

	//----------------------------------------------------------------
	// Custom Functions
	// We recommend adding all your custom code down here at the bottom
	//----------------------------------------------------------------

	public function __toString()
	{
		return $this->name;
	}	
}
