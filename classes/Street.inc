<?php
/**
* @copyright Copyright (C) 2006 City of Bloomington, Indiana. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
*/
	class Street extends ActiveRecord
	{
		private $id;
		private $notes;
		private $streetStatus_id;

		private $status;

		private $streetName;
		private $streetNameList;

		private $segments;
		private $deletedSegmentIds = array();
		private $newSegmentIds = array();

		public function __construct($id=null)
		{
			global $PDO;

			if ($id)
			{
				$sql = "select * from streets where id=$id";
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
			}
		}


		public function save()
		{
			# Check for required fields here.  Throw an exception if anything is missing.


			$fields = array();
			if ($this->notes) { $fields[] = "notes='{$this->notes}'"; } else { $fields[] = "notes=null"; }
			if ($this->streetStatus_id) { $fields[] = "streetStatus_id={$this->streetStatus_id}"; } else { $fields[] = "streetStatus_id=null"; }
			$fields = implode(",",$fields);


			if ($this->id) { $this->update($fields); }
			else { $this->insert($fields); }

			$this->saveSegments();
		}

		private function update($fields)
		{
			global $PDO;

			$sql = "update streets set $fields where id={$this->id}";
			if (false === $PDO->exec($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
		}

		private function insert($fields)
		{
			global $PDO;

			$sql = "insert streets set $fields";
			if (false === $PDO->exec($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
			$this->id = $PDO->lastInsertID();
		}

		public function addSegment($segment)
		{
			$this->getSegments();
			$this->newSegmentIds[] = $segment->getId();
			$this->segments[] = $segment;
		}
		public function deleteSegment($segment_id)
		{
			foreach($this->getSegments() as $i=>$segment)
			{
				if ($segment->getId() == $segment_id)
				{
					unset($this->segments[$i]);
					$this->deletedSegmentIds[] = $segment_id;
				}
			}
		}
		private function saveSegments()
		{
			global $PDO;

			# Clear out any deleted segments
			if (count($this->deletedSegmentIds))
			{
				$ids = implode(",",$this->deletedSegmentIds);
				$sql = "delete from street_segments where street_id={$this->id} and segment_id in ($ids)";
				if (false === $PDO->exec($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
			}

			# Add in any new segments
			if (count($this->newSegmentIds))
			{
				$statement = $PDO->prepare("insert street_segments values({$this->id},?)");
				foreach($this->newSegmentIds as $segment_id)
				{
					try { $statement->execute(array("$segment_id")); }
					catch (PDOException $e) { throw $e; }
				}
			}
		}


		public function getId() { return $this->id; }
		public function getNotes() { return $this->notes; }
		public function getStreetStatus_id() { return $this->streetStatus_id; }
		public function getStatus_id() { return $this->streetStatus_id; }
		public function getStatus()
		{
			if ($this->streetStatus_id)
			{
				if (!$this->status) { $this->status = new Status("street",$this->streetStatus_id); }
				return $this->status;
			}
			else return null;
		}
		public function getSegments()
		{
			if (!count($this->segments)) { $this->segments = new SegmentList(array('street_id'=>$this->id)); }
			return $this->segments;
		}
		/*
		* Returns the primary name for this street
		*/
		public function getStreetName()
		{
			if (!$this->streetName)
			{
				foreach($this->getStreetNames() as $streetName)
				{
					if ($streetName->getStreetNameType()->getType() == "STREET") { $this->streetName = $streetName; }
				}
			}
			return $this->streetName;
		}
		public function getFullStreetName()
		{
			if ($this->getStreetName())
			{
				return $this->getStreetName()->getName()->getFullName();
			}
			else return "";
		}
		public function getStreetNames()
		{
			if (!count($this->streetNameList)) { $this->streetNameList = new StreetNameList(array("street_id"=>$this->id)); }
			return $this->streetNameList;
		}


		public function __toString() { return $this->getStreetName()->getName()->getFullname(); }

		public function setNotes($string) { $this->notes = trim($string); }
		public function setStreetStatus_id($int) { $this->status = new Status("street",$int); $this->streetStatus_id = $int; }
		public function setStatus_id($int) { $this->setStreetStatus_id($int); }
		public function setStatus($status) { $this->streetStatus_id = $status->getId(); $this->status = $status; }
	}
?>