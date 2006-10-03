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
				$sql = "select * from plats where id=$id";
				$result = $PDO->query($sql);
				if ($result)
				{
					if ($row = $result->fetch())
					{
						# This will load all fields in the table as properties of this class.
						# You may want to replace this with, or add your own extra, custom loading
						foreach($row as $field=>$value) { if ($value) $this->$field = $value; }


						$result->closeCursor();
					}
					else { throw new Exception("unknownPlat"); }
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
			if (!$this->name) { throw new Exception("missingRequiredFields"); }


			$fields = array();
			$fields[] = "name='{$this->name}'";
			if ($this->township_id) { $fields[] = "township_id={$this->township_id}"; } else { $fields[] = "township_id=null"; }
			if ($this->platType_id) { $fields[] = "platType_id={$this->platType_id}"; } else { $fields[] = "platType_id=null"; }
			if ($this->cabinet) { $fields[] = "cabinet='{$this->cabinet}'"; } else { $fields[] = "cabinet=null"; }
			if ($this->envelope) { $fields[] = "envelope='{$this->envelope}'"; } else { $fields[] = "envelope=null"; }
			if ($this->notes) { $fields[] = "notes='{$this->notes}'"; } else { $fields[] = "notes=null"; }
			$fields = implode(",",$fields);


			if ($this->id) { $this->update($fields); }
			else { $this->insert($fields); }
		}

		private function update($fields)
		{
			global $PDO;

			$sql = "update plats set $fields where id={$this->id}";
			if (false === $PDO->exec($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
		}

		private function insert($fields)
		{
			global $PDO;

			$sql = "insert plats set $fields";
			if (false === $PDO->exec($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
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