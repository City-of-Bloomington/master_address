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
				$sql = "select * from buildings where id=$id";
				$result = $PDO->query($sql);
				if ($result)
				{
					if ($row = $result->fetch())
					{
						foreach($row as $field=>$value) { if ($value) $this->$field = $value; }

						$result->closeCursor();
					}
					else { throw new Exception($sql); }
				}
				else { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
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


		public function save()
		{
			# Check for required fields here.  Throw an exception if anything is missing.


			# This generates generic SQL that should work right away.
			# You can (and maybe should) replace this $fields code with your own custom SQL
			# for each property of this class,
			$fields = array();
			$fields[] = $this->name ? "name='{$this->name}'" : "name=null";
			$fields[] = $this->startDate ? "startDate='{$this->startDate}'" : "startDate=null";
			$fields[] = $this->endDate ? "endDate='{$this->endDate}'" : "endDate=null";
			$fields[] = $this->GISTag ? "GISTag='{$this->GISTag}'" : "GISTag=null";
			$fields[] = $this->buildingStatus_id ? "buildingStatus_id='{$this->buildingStatus_id}'" : "buildingStatus_id=null";

			$fields = implode(",",$fields);


			if ($this->id) { $this->update($fields); }
			else { $this->insert($fields); }
		}

		private function update($fields)
		{
			global $PDO;

			$sql = "update buildings set $fields where id={$this->id}";
			if (false === $PDO->exec($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
		}

		private function insert($fields)
		{
			global $PDO;

			$sql = "insert buildings set $fields";
			if (false === $PDO->exec($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
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