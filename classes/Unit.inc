<?php
/**
* @copyright Copyright (C) 2006 City of Bloomington, Indiana. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
*/
	class Unit extends ActiveRecord
	{
		private $id;
		private $place_id;
		private $unitType_id;
		private $building_id;
		private $identifier;
		private $mailable;
		private $livable;
		private $notes;
		private $unitStatus_id;

		private $place;
		private $unitType;
		private $building;
		private $status;


		public function __construct($id=null)
		{
			global $PDO;

			if ($id)
			{
				$sql = "select * from units where id=?";
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
				$this->unitStatus_id = 1;
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
			if (!$this->place_id || !$this->unitType_id || !$this->unitStatus_id) { throw new Exception("missingRequiredFields"); }

			$fields = array();
			$fields['place_id'] = $this->place_id ? $this->place_id : null;
			$fields['unitType_id'] = $this->unitType_id ? $this->unitType_id : null;
			$fields['building_id'] = $this->building_id ? $this->building_id : null;
			$fields['identifier'] = $this->identifier ? $this->identifier : null;
			$fields['mailable'] = $this->mailable ? $this->mailable : null;
			$fields['livable'] = $this->livable ? $this->livable : null;
			$fields['notes'] = $this->notes ? $this->notes : null;
			$fields['unitStatus_id'] = $this->unitStatus_id ? $this->unitStatus_id : null;

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

			$sql = "update units set $preparedFields where id={$this->id}";
			if (false === $query = $PDO->prepare($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
			if (false === $query->execute($values)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
		}

		private function insert($values,$preparedFields)
		{
			global $PDO;

			$sql = "insert units set $preparedFields";
			if (false === $query = $PDO->prepare($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
			if (false === $query->execute($values)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
			$this->id = $PDO->lastInsertID();
		}


		public function getId() { return $this->id; }
		public function getPlace_id() { return $this->place_id; }
		public function getUnitType_id() { return $this->unitType_id; }
		public function getBuilding_id() { return $this->building_id; }
		public function getIdentifier() { return $this->identifier; }
		public function getMailable() { return $this->mailable; }
		public function getLivable() { return $this->livable; }
		public function getNotes() { return $this->notes; }
		public function getUnitStatus_id() { return $this->unitStatus_id; }
		public function getStatus_id() { return $this->unitStatus_id; }
		public function getPlace()
		{
			if ($this->place_id)
			{
				if (!$this->place) { $this->place = new Place($this->place_id); }
				return $this->place;
			}
			else return null;
		}
		public function getUnitType()
		{
			if ($this->unitType_id)
			{
				if (!$this->unitType) { $this->unitType = new UnitType($this->unitType_id); }
				return $this->unitType;
			}
			else return null;
		}
		public function getType() { return $this->getUnitType(); }
		public function getBuilding()
		{
			if ($this->building_id)
			{
				if (!$this->building) { $this->building = new Building($this->building_id); }
				return $this->building;
			}
			else return null;
		}
		public function getStatus()
		{
			if (!$this->unitStatus_id) { $this->unitStatus_id = 1; }
			if (!$this->status) { $this->status = new Status("unit",$this->unitStatus_id); }
			return $this->status;
		}


		public function setPlace_id($int) { $this->place = new Place($int); $this->place_id = $int; }
		public function setUnitType_id($int) { $this->unitType = new UnitType($int); $this->unitType_id = $int; }
		public function setBuilding_id($int) { $this->building = new Building($int); $this->building_id = $int; }
		public function setIdentifier($string) { $this->identifier = trim($string); }
		public function setMailable($int) { $this->mailable = ereg_replace("[^0-9]","",$int); }
		public function setLivable($int) { $this->livable = ereg_replace("[^0-9]","",$int); }
		public function setNotes($string) { $this->notes = trim($string); }
		public function setUnitStatus_id($int) { $this->status = new Status("unit",$int); $this->unitStatus_id = $int; }
		public function setStatus_id($int) { $this->setUnitStatus_id($int); }
		public function setStatus($status) { $this->unitStatus_id = $status->getId(); $this->status = $status; }

		public function setPlace($place) { $this->place_id = $place->getId(); $this->place = $place; }
		public function setBuilding($building) { $this->building_id = $building->getId(); $this->building = $building; }
	}
?>