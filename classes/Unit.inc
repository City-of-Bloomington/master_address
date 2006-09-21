<?php
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
				$sql = "select * from units where id=$id";
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
					else { throw new Exception($sql); }
				}
				else { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
			}
			else
			{
				# This is where the code goes to generate a new, empty instance.
				# Set any default values for properties that need it here
				$this->unitStatus_id = 1;
			}
		}


		public function save()
		{
			# Check for required fields here.  Throw an exception if anything is missing.
			if (!$this->place_id || !$this->unitType_id || !$this->unitStatus_id) { throw new Exception("missingRequiredFields"); }


			$fields = array();
			$fields[] = "place_id={$this->place_id}";
			$fields[] = "unitType_id={$this->unitType_id}";
			$fields[] = $this->building_id ? "building_id={$this->building_id}" : "building_id=null";
			$fields[] = $this->identifier ? "identifier='{$this->identifier}'" : "identifier=null";
			$fields[] = $this->mailable ? "mailable='{$this->mailable}'" : "mailable=null";
			$fields[] = $this->livable ? "livable='{$this->livable}'" : "livable=null";
			$fields[] = $this->notes ? "notes='{$this->notes}'" : "notes=null";
			$fields[] = $this->unitStatus_id ? "unitStatus_id={$this->unitStatus_id}" : "unitStatus_id=null";
			$fields = implode(",",$fields);


			if ($this->id) { $this->update($fields); }
			else { $this->insert($fields); }
		}

		private function update($fields)
		{
			global $PDO;

			$sql = "update units set $fields where id={$this->id}";
			if (false === $PDO->exec($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
		}

		private function insert($fields)
		{
			global $PDO;

			$sql = "insert units set $fields";
			if (false === $PDO->exec($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
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
		public function getUnitStatus_id { return $this->unitStatus_id; }
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