<?php
/**
* @copyright Copyright (C) 2006 City of Bloomington, Indiana. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
*/
	class Building extends ActiveRecord
	{
		private $id;
		private $name;
		private $startDate;
		private $endDate;
		private $GISTag;
		private $buildingStatus_id;

		private $status;
		private $unitList;

		public function __construct($id=null)
		{
			global $PDO;

			if ($id)
			{
				$sql = "select * from buildings where id=?";
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
				$this->startDate = $this->dateArrayToString(getdate());
				$this->status = new Status("building",1);
				$this->buildingStatus_id = 1;
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

			$fields = array();
			$fields['name'] = $this->name ? $this->name : null;
			$fields['startDate'] = $this->startDate ? $this->startDate : null;
			$fields['endDate'] = $this->endDate ? $this->endDate : null;
			$fields['GISTag'] = $this->GISTag ? $this->GISTag : null;
			$fields['buildingStatus_id'] = $this->buildingStatus_id ? $this->buildingStatus_id : null;

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

			$sql = "update buildings set $preparedFields where id={$this->id}";
			if (false === $query = $PDO->prepare($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
			if (false === $query->execute($values)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
		}

		private function insert($values,$preparedFields)
		{
			global $PDO;

			$sql = "insert buildings set $preparedFields";
			if (false === $query = $PDO->prepare($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
			if (false === $query->execute($values)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
			$this->id = $PDO->lastInsertID();
		}


		public function getId() { return $this->id; }
		public function getName() { return $this->name; }
		public function getStartDate() { return $this->startDate; }
		public function getEndDate() { return $this->endDate; }
		public function getGISTag() { return $this->GISTag; }
		public function getBuildingStatus_id() { return $this->buildingStatus_id; }
		public function getStatus_id() { return $this->buildingStatus_id; }
		public function getStatus()
		{
			if (!$this->status) { $this->status = new Status("building",$this->buildingStatus_id); }
			return $this->status;
		}
		public function getUnits()
		{
			if (!$this->unitList) { $this->unitList = new UnitList(array("building_id"=>$this->id)); }
			return $this->unitList;
		}
		public function getPlaceList() { return new PlaceList(array("building_id"=>$this->id)); }


		public function setName($string) { $this->name = trim($string); }
		public function setStartDate($date) { $this->startDate = is_array($date) ? $this->dateArrayToString($date) : $date; }
		public function setEndDate($date) { $this->endDate = is_array($date) ? $this->dateArrayToString($date) : $date; }
		public function setGISTag($string) { $this->GISTag = trim($string); }
		public function setBuildingStatus_id($int) { $this->status = new Status("building",$int); $this->buildingStatus_id = $int; }
		public function setStatus_id($int) { $this->setBuildingStatus_id($int); }
		public function setStatus($status) { $this->buildingStatus_id = $status->getId(); $this->status = $status; }
	}
?>