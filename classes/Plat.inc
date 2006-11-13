<?php
/**
* @copyright Copyright (C) 2006 City of Bloomington, Indiana. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
*/
	class Plat extends ActiveRecord
	{
		private $id;
		private $name;
		private $township_id;
		private $platType_id;
		private $cabinet;
		private $envelope;
		private $notes;

		private $township;
		private $platType;
		private $placeList;



		public function __construct($id=null)
		{
			global $PDO;

			if ($id)
			{
				$sql = "select * from plats where id=?";
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
			$fields['name'] = $this->name ? $this->name : null;
			$fields['township_id'] = $this->township_id ? $this->township_id : null;
			$fields['platType_id'] = $this->platType_id ? $this->platType_id : null;
			$fields['cabinet'] = $this->cabinet ? $this->cabinet : null;
			$fields['envelope'] = $this->envelope ? $this->envelope : null;
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

			$sql = "update plats set $preparedFields where id={$this->id}";
			if (false === $query = $PDO->prepare($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
			if (false === $query->execute($values)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
		}

		private function insert($values,$preparedFields)
		{
			global $PDO;

			$sql = "insert plats set $preparedFields";
			if (false === $query = $PDO->prepare($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
			if (false === $query->execute($values)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
			$this->id = $PDO->lastInsertID();
		}


		public function __toString() { return $this->name; }

		public function getId() { return $this->id; }
		public function getName() { return $this->name; }
		public function getTownship_id() { return $this->township_id; }
		public function getPlatType_id() { return $this->platType_id; }
		public function getCabinet() { return $this->cabinet; }
		public function getEnvelope() { return $this->envelope; }
		public function getNotes() { return $this->notes; }
		public function getType() { return $this->getPlatType(); }
		public function getPlaces() { return $this->getPlaceList(); }
		public function getTownship()
		{
			if ($this->township_id)
			{
				if (!$this->township) { $this->township = new Township($this->township_id); }
				return $this->township;
			}
			else return new Township();
		}
		public function getPlatType()
		{
			if ($this->platType_id)
			{
				if (!$this->platType) { $this->platType = new PlatType($this->platType_id); }
				return $this->platType;
			}
			else return null;
		}
		public function getPlaceList()
		{
			if (!$this->placeList) { $this->placeList = new PlaceList(array('plat_id'=>$this->id)); }
			return $this->placeList;
		}


		public function setName($string) { $this->name = trim($string); }
		public function setTownship_id($int) { $this->township = new Township($int); $this->township_id = $int; }
		public function setPlatType_id($int) { $this->platType = new PlatType($int); $this->platType_id = $int; }
		public function setCabinet($string) { $this->cabinet = trim($string); }
		public function setEnvelope($string) { $this->envelope = trim($string); }
		public function setNotes($string) { $this->notes = trim($string); }
	}
?>