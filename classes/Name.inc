<?php
/**
* @copyright Copyright (C) 2006 City of Bloomington, Indiana. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
*/
	class Name extends ActiveRecord
	{
		private $id;
		private $town_id;
		private $direction_id;
		private $name;
		private $suffix_id;
		private $postDirection_id;
		private $startDate;
		private $endDate;
		private $notes;

		private $fullname;

		private $town;
		private $direction;
		private $suffix;
		private $postDirection;

		private $streetNameList;
		private $streets = array();

		public function __construct($id=null)
		{
			global $PDO;

			if ($id)
			{
				$sql = "select * from names where id=?";
				try
				{
					$query = $PDO->prepare($sql);
					$query->execute(array($id));
				}
				catch (Exception $e) { throw $e; }

				$result = $query->fetchAll();
				foreach($result[0] as $field=>$value) { if ($value) $this->$field = $value; }
			}
			else
			{
				# This is where the code goes to generate a new, empty instance.
				# Set any default values for properties that need it here
			}
		}


		/**
		 * This generates generic SQL that should work right away.
		 * You can replace this $fields code with your own custom SQL
		 * for each property of this class,
		 */
		public function save()
		{
			# Check for required fields here.  Throw an exception if anything is missing.
			if (!$this->name) { throw new Exception("missingRequiredFields"); }


			$fields = array();
			$fields['town_id'] = $this->town_id ? $this->town_id : null;
			$fields['direction_id'] = $this->direction_id ? $this->direction_id : null;
			$fields['name'] = $this->name ? $this->name : null;
			$fields['suffix_id'] = $this->suffix_id ? $this->suffix_id : null;
			$fields['postDirection_id'] = $this->postDirection_id ? $this->postDirection_id : null;
			$fields['startDate'] = $this->startDate ? $this->startDate : null;
			$fields['endDate'] = $this->endDate ? $this->endDate : null;
			$fields['notes'] = $this->notes ? $this->notes : null;

			# Split the fields up into a preparedFields array and a values array.
			# PDO->execute cannot take an associative array for values, so we have
			# to strip out the keys from $fields
			$preparedFields = array();
			foreach($fields as $key=>$value)
			{
				$preparedFields[] = "$key=?";
				$values[] = $value;
			}
			$preparedFields = implode(",",$preparedFields);


			if ($this->id) { $this->update($values,$preparedFields); }
			else { $this->insert($values,$preparedFields); }
		}

		private function update($values,$preparedFields)
		{
			global $PDO;

			$sql = "update names set $preparedFields where id={$this->id}";
			if (false === $query = $PDO->prepare($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
			if (false === $query->execute($values)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
		}

		private function insert($values,$preparedFields)
		{
			global $PDO;

			$sql = "insert names set $preparedFields";
			if (false === $query = $PDO->prepare($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
			if (false === $query->execute($values)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
			$this->id = $PDO->lastInsertID();
		}

		public function __toString() { return $this->getFullname(); }

		public function getId() { return $this->id; }
		public function getTown_id() { return $this->town_id; }
		public function getDirection_id() { return $this->direction_id; }
		public function getName() { return $this->name; }
		public function getSuffix_id() { return $this->suffix_id; }
		public function getPostDirection_id() { return $this->postDirection_id; }
		public function getStartDate() { return $this->startDate; }
		public function getEndDate() { return $this->endDate; }
		public function getNotes() { return $this->notes; }
		public function getFullname()
		{
			if (!$this->fullname)
			{
				$temp = array();
				if ($this->direction_id) { $temp[] = $this->getDirection()->getCode(); }
				$temp[] = $this->name;
				if ($this->suffix_id) { $temp[] = $this->getSuffix()->getSuffix(); }
				if ($this->postDirection_id) { $temp[] = $this->getPostDirection()->getCode(); }
				$this->fullname = implode(" ",$temp);
			}
			return $this->fullname;
		}
		public function getTown()
		{
			if ($this->town_id)
			{
				if (!$this->town) { $this->town = new Town($this->town_id); }
				return $this->town;
			}
			else return null;
		}
		public function getDirection()
		{
			if ($this->direction_id)
			{
				if (!$this->direction) { $this->direction = new Direction($this->direction_id); }
				return $this->direction;
			}
			else return null;
		}
		public function getSuffix()
		{
			if ($this->suffix_id)
			{
				if (!$this->suffix) { $this->suffix = new Suffix($this->suffix_id); }
				return $this->suffix;
			}
			else return null;
		}
		public function getPostDirection()
		{
			if ($this->postDirection_id)
			{
				if (!$this->postDirection) { $this->postDirection = new Direction($this->postDirection_id); }
				return $this->postDirection;
			}
			else return null;
		}
		public function getStreetNameList()
		{
			if (!$this->streetNameList)
			{
				$this->streetNameList = new StreetNameList();
				$this->streetNameList->find(array("name_id"=>$this->id));
			}
			return $this->streetNameList;
		}
		public function getStreetNames() { return $this->getStreetNameList(); }
		public function getStreets()
		{
			if (!count($this->streets))
			{
				foreach($this->getStreetNameList() as $streetName) { $this->streets[] = $streetName->getStreet(); }
			}
			return $this->streets;
		}


		public function setTown_id($int) { $this->town = new Town($int); $this->town_id = $int; }
		public function setDirection_id($int) { $this->direction = new Direction($int); $this->direction_id = $int; }
		public function setName($string) { $this->name = trim($string); }
		public function setSuffix_id($int) { $this->suffix = new Suffix($int); $this->suffix_id = $int; }
		public function setPostDirection_id($int) { $this->postDirection = new Direction($int); $this->postDirection_id = $int; }
		public function setStartDate($date) { $this->startDate = is_array($date) ? $this->dateArrayToString($date) : $date; }
		public function setEndDate($date) { $this->endDate = is_array($date) ? $this->dateArrayToString($date) : $date; }
		public function setNotes($string) { $this->notes = trim($string); }
	}
?>