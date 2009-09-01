<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
class Annexation
{
	private $id;
	private $ordinance_number;
	private $township_id;
	private $name;
	private $passed_date;
	private $effective_start_date;
	private $annexation_type;
	private $acres;
	private $square_miles;
	private $estimate_population;
	private $dwelling_units;

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
				if (is_numeric($id)) {
					$sql = 'select * from annexations where id=?';
				}
				else {
					$sql = 'select * from annexations where ordinance_number=?';
				}
				$result = $zend_db->fetchRow($sql,array($id));
			}

			if ($result) {
				foreach ($result as $field=>$value) {
					if ($value) {
						if (preg_match('/date/',$field) && $value!='0000-00-00') {
							$value = new Date($value);
						}
						$this->$field = $value;
					}
				}
			}
			else {
				throw new Exception('annexations/unknownAnnexation');
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
		if (!$this->ordinance_number) {
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
		$data['ordinance_number'] = $this->ordinance_number;
		$data['township_id'] = $this->township_id ? $this->township_id : null;
		$data['name'] = $this->name ? $this->name : null;
		$data['passed_date'] = $this->passed_date ? $this->passed_date->format('Y-m-d') : null;
		$data['effective_start_date'] = $this->effective_start_date ? $this->effective_start_date->format('Y-m-d') : null;
		$data['annexation_type'] = $this->annexation_type ? $this->annexation_type : null;
		$data['acres'] = $this->acres ? $this->acres : null;
		$data['square_miles'] = $this->square_miles ? $this->square_miles : null;
		$data['estimate_population'] = $this->estimate_population ? $this->estimate_population : null;
		$data['dwelling_units'] = $this->dwelling_units ? $this->dwelling_units : null;

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
		$zend_db->update('annexations',$data,"id='{$this->id}'");
	}

	private function insert($data)
	{
		$zend_db = Database::getConnection();
		$zend_db->insert('annexations',$data);
		if (Database::getType()=='oracle') {
			$this->id = $zend_db->lastSequenceId('annexations_id_seq');
		}
		else {
			$this->id = $zend_db->lastInsertId();
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
	 * @return string
	 */
	public function getOrdinance_number()
	{
		return $this->ordinance_number;
	}

	/**
	 * @return number
	 */
	public function getTownship_id()
	{
		return $this->township_id;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Returns the date/time in the desired format
	 *
	 * Format is specified using PHP's date() syntax
	 * http://www.php.net/manual/en/function.date.php
	 * If no format is given, the Date object is returned
	 *
	 * @param string $format
	 * @return string|DateTime
	 */
	public function getPassed_date($format=null)
	{
		if ($format && $this->passed_date) {
			return $this->passed_date->format($format);
		}
		else {
			return $this->passed_date;
		}
	}

	/**
	 * Returns the date/time in the desired format
	 *
	 * Format is specified using PHP's date() syntax
	 * http://www.php.net/manual/en/function.date.php
	 * If no format is given, the Date object is returned
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
	 * @return number
	 */
	public function getAnnexation_type()
	{
		return $this->annexation_type;
	}

	/**
	 * @return number
	 */
	public function getAcres()
	{
		return $this->acres;
	}

	/**
	 * @return number
	 */
	public function getSquare_miles()
	{
		return $this->square_miles;
	}

	/**
	 * @return number
	 */
	public function getEstimate_population()
	{
		return $this->estimate_population;
	}

	/**
	 * @return number
	 */
	public function getDwelling_units()
	{
		return $this->dwelling_units;
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
	public function setOrdinance_number($string)
	{
		$this->ordinance_number = trim($string);
	}

	/**
	 * @param number $number
	 */
	public function setTownship_id($number)
	{
		$this->township_id = $number;
	}

	/**
	 * @param string $string
	 */
	public function setName($string)
	{
		$this->name = trim($string);
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
	public function setPassed_date($date)
	{
		if ($date) {
			$this->passed_date = new Date($date);
		}
		else {
			$this->passed_date = null;
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
	 * @param number $number
	 */
	public function setAnnexation_type($number)
	{
		$this->annexation_type = $number;
	}

	/**
	 * @param number $number
	 */
	public function setAcres($number)
	{
		$this->acres = $number;
	}

	/**
	 * @param number $number
	 */
	public function setSquare_miles($number)
	{
		$this->square_miles = $number;
	}

	/**
	 * @param number $number
	 */
	public function setEstimate_population($number)
	{
		$this->estimate_population = $number;
	}

	/**
	 * @param number $number
	 */
	public function setDwelling_units($number)
	{
		$this->dwelling_units = $number;
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
}
