<?php
/**
* @copyright Copyright (C) 2006 City of Bloomington, Indiana. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
*/
	class Township extends ActiveRecord
	{
		private $id;
		private $name;
		private $abbreviation;
		private $quarterCode;



		public function __construct($id=null)
		{
			global $PDO;

			if ($id)
			{
				$sql = "select * from townships where id=?";
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
			if (!$this->name || !$this->abbreviation) { throw new Exception("missingRequiredFields"); }

			$fields = array();
			$fields['name'] = $this->name ? $this->name : null;
			$fields['abbreviation'] = $this->abbreviation ? $this->abbreviation : null;
			$fields['quarterCode'] = $this->quarterCode ? $this->quarterCode : null;

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

			$sql = "update townships set $preparedFields where id={$this->id}";
			if (false === $query = $PDO->prepare($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
			if (false === $query->execute($values)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
		}

		private function insert($values,$preparedFields)
		{
			global $PDO;

			$sql = "insert townships set $preparedFields";
			if (false === $query = $PDO->prepare($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
			if (false === $query->execute($values)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
			$this->id = $PDO->lastInsertID();
		}

		public function __toString() { return $this->name; }


		public function getId() { return $this->id; }
		public function getName() { return $this->name; }
		public function getAbbreviation() { return $this->abbreviation; }
		public function getQuarterCode() { return $this->quarterCode; }


		public function setName($string) { $this->name = trim($string); }
		public function setAbbreviation($char) { $this->abbreviation = ereg_replace("[^A-Z]","",strtoupper($char)); }
		public function setQuarterCode($char) { $this->quarterCode = ereg_replace("[^A-Z]","",strtoupper($char)); }
	}
?>