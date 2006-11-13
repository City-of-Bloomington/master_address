<?php
/**
* @copyright Copyright (C) 2006 City of Bloomington, Indiana. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
*/
	class StreetName extends ActiveRecord
	{
		private $id;
		private $street_id;
		private $name_id;
		private $streetNameType_id;

		private $street;
		private $name;
		private $streetNameType;



		public function __construct($id=null)
		{
			global $PDO;

			if ($id)
			{
				$sql = "select * from streetNames where id=?";
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
			if (!$this->street_id || !$this->name_id || !$this->streetNameType_id) { throw new Exception("missingRequiredFields"); }

			$fields = array();
			$fields['street_id'] = $this->street_id ? $this->street_id : null;
			$fields['name_id'] = $this->name_id ? $this->name_id : null;
			$fields['streetNameType_id'] = $this->streetNameType_id ? $this->streetNameType_id : null;

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

			$sql = "update streetNames set $preparedFields where id={$this->id}";
			if (false === $query = $PDO->prepare($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
			if (false === $query->execute($values)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
		}

		private function insert($values,$preparedFields)
		{
			global $PDO;

			$sql = "insert streetNames set $preparedFields";
			if (false === $query = $PDO->prepare($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
			if (false === $query->execute($values)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
			$this->id = $PDO->lastInsertID();
		}


		public function __toString()
		{
			return $this->getFullname();
		}

		public function getId() { return $this->id; }
		public function getStreet_id() { return $this->street_id; }
		public function getName_id() { return $this->name_id; }
		public function getStreetNameType_id() { return $this->streetNameType_id; }
		public function getStreet()
		{
			if ($this->street_id)
			{
				if (!$this->street) { $this->street = new Street($this->street_id); }
				return $this->street;
			}
			else return null;
		}
		public function getName()
		{
			if ($this->name_id)
			{
				if (!$this->name) { $this->name = new Name($this->name_id); }
				return $this->name;
			}
			else return null;
		}
		public function getFullname() { return $this->getName()->getFullname(); }
		public function getStreetNameType()
		{
			if ($this->streetNameType_id)
			{
				if (!$this->streetNameType) { $this->streetNameType = new StreetNameType($this->streetNameType_id); }
				return $this->streetNameType;
			}
			else return null;
		}
		public function getType() { return $this->getStreetNameType()->getType(); }


		public function setStreet_id($int) { $this->street = new Street($int); $this->street_id = $int; }
		public function setName_id($int) { $this->name = new Name($int); $this->name_id = $int; }
		public function setStreetNameType_id($int) { $this->streetNameType = new StreetNameType($int); $this->streetNameType_id = $int; }
		public function setStreet($street) { $this->street = $street; $this->street_id = $street->getId(); }
		public function setName($name) { $this->name = $name; $this->name_id = $name->getId(); }
		public function setStreetNameType($type) { $this->streetNameType = $type; $this->streetNameType_id = $type->getId(); }
	}
?>