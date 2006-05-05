<?php
	require_once(APPLICATION_HOME."/classes/Township.inc");
	require_once(APPLICATION_HOME."/classes/PlatType.inc");

	class Plat
	{
		private $platID;
		private $name;
		private $townshipID;
		private $type;
		private $cabinet;
		private $envelope;
		private $notes;

		private $township;
		private $platType;



		public function __construct($platID=null)
		{
			global $PDO;

			if ($platID)
			{
				$sql = "select * from plats where platID=$platID";
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
			}
		}


		public function save()
		{
			# Check for required fields here.  Throw an exception if anything is missing.
			if (!$this->name || !$this->townshipID || !$this->type) { throw new Exception("missingRequiredFields"); }


			$fields = array();
			$fields[] = "name='{$this->name}'";
			$fields[] = "townshipID={$this->townshipID}";
			$fields[] = "type='{$this->type}'";
			if ($this->cabinet) { $fields[] = "cabinet='{$this->cabinet}'"; } else { $fields[] = "cabinet=null"; }
			if ($this->envelope) { $fields[] = "envelope='{$this->envelope}'"; } else { $fields[] = "envelope=null"; }
			if ($this->notes) { $fields[] = "notes='{$this->notes}'"; } else { $fields[] = "notes=null"; }
			$fields = implode(",",$fields);


			if ($this->platID) { $this->update($fields); }
			else { $this->insert($fields); }
		}

		private function update($fields)
		{
			global $PDO;

			$sql = "update plats set $fields where platID={$this->platID}";
			if (false === $PDO->exec($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
		}

		private function insert($fields)
		{
			global $PDO;

			$sql = "insert plats set $fields";
			if (false === $PDO->exec($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
			$this->platID = $PDO->lastInsertID();
		}


		public function getPlatID() { return $this->platID; }
		public function getName() { return $this->name; }
		public function getTownshipID() { return $this->townshipID; }
		public function getType() { return $this->type; }
		public function getCabinet() { return $this->cabinet; }
		public function getEnvelope() { return $this->envelope; }
		public function getNotes() { return $this->notes; }
		public function getTownship()
		{
			if (!$this->township) { $this->township = new Township($this->townshipID); }
			return $this->township;
		}
		public function getPlatType()
		{
			if (!$this->platType) { $this->platType = new PlatType($this->type); }
			return $this->platType;
		}


		public function setName($string) { $this->name = $string; }
		public function setCabinet($string) { $this->cabinet = $string; }
		public function setEnvelope($string) { $this->envelope = $string; }
		public function setNotes($string) { $this->notes = $string; }
		public function setTownshipID($townshipID)
		{
			$this->township = new Township($townshipID);
			$this->townshipID = $townshipID;
		}
		public function setType($type)
		{
			$this->platType = new PlatType($type);
			$this->type = $type;
		}
	}
?>