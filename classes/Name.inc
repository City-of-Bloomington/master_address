<?php
	require_once(APPLICATION_HOME."/classes/Town.inc");
	require_once(APPLICATION_HOME."/classes/Suffix.inc");
	require_once(APPLICATION_HOME."/classes/Direction.inc");
	class Name
	{
		private $nameID;
		private $townID;
		private $directionCode;
		private $name;
		private $suffixAbbreviation;
		private $postDirectionCode;
		private $startDate;
		private $endDate;
		private $notes;

		# Caches for objects
		private $town;
		private $suffix;
		private $direction;
		private $postDirection;

		public function __construct($nameID=null)
		{
			global $PDO;

			if ($nameID)
			{
				$sql = "select * from names where nameID=$nameID";
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
			if (!$this->name) { throw new Exception("missingRequiredFields"); }

			if ($this->nameID) { $this->update(); }
			else { $this->insert(); }
		}

		private function update()
		{
			global $PDO;

			$fields = array();
			$fields[] = "name='{$this->name}'";
			if ($this->townID)				{ $fields[] = "townID={$this->townID}"; } else { $fields[] = "townID=null"; }
			if ($this->directionCode)		{ $fields[] = "directionCode='{$this->directionCode}'"; } else { $fields[] = "directionCode=null"; }
			if ($this->suffixAbbreviation)	{ $fields[] = "suffixAbbreviation='{$this->suffixAbbreviation}'"; } else { $fields[] = "suffixAbbreviation=null"; }
			if ($this->postDirectionCode)	{ $fields[] = "postDirectionCode='{$this->postDirectionCode}'"; } else { $fields[] = "postDirectionCode=null"; }
			if ($this-startDate)			{ $fields[] = "startDate='{$this->startDate}'"; } else { $fields[] = "startDate=null"; }
			if ($this->endDate)				{ $fields[] = "endDate='{$this->endDate}'"; } else { $fields[] = "endDate=null"; }
			if ($this->notes)				{ $fields[] = "notes='{$this->notes}'"; } else { $fields[] = "notes=null"; }
			$fields = implode(",",$fields);

			$sql = "update names set $fields where nameID={$this->nameID}";
			if (false === $PDO->exec($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
		}

		private function insert()
		{
			global $PDO;

			$fields = array();
			$fields[] = "name='{$this->name}'";
			if ($this->townID)				{ $fields[] = "townID={$this->townID}"; }
			if ($this->directionCode)		{ $fields[] = "directionCode='{$this->directionCode}'"; }
			if ($this->suffixAbbreviation)	{ $fields[] = "suffixAbbreviation='{$this->suffixAbbreviation}'"; }
			if ($this->postDirectionCode)	{ $fields[] = "postDirectionCode='{$this->postDirectionCode}'"; }
			if ($this-startDate)			{ $fields[] = "startDate='{$this->startDate}'"; } else { $fields[] = "startDate=now()"; }
			if ($this->endDate)				{ $fields[] = "endDate='{$this->endDate}'"; }
			if ($this->notes)				{ $fields[] = "notes='{$this->notes}'"; }
			$fields = implode(",",$fields);

			$sql = "insert names set $fields";
			if (false === $PDO->exec($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
			$this->nameID = $PDO->lastInsertID();
		}


		public function getNameID() { return $this->nameID; }
		public function getTownID() { return $this->townID; }
		public function getTown()
		{
			if (!$this->town) { $this->town = new Town($this->townID); }
			return $this->town;
		}
		public function getDirectionCode() { return $this->directionCode; }
		public function getDirection()
		{
			if (!$this->direction) { $this->direction = new Direction($this->directionCode); }
			return $this->direction;
		}
		public function getName() { return $this->name; }
		public function getSuffixAbbreviation() { return $this->suffixAbbreviation; }
		public function getSuffix()
		{
			if (!$this->suffix) { $this->suffix = new Suffix($this->suffixAbbreviation); }
			return $this->suffix;
		}
		public function getPostDirectionCode() { return $this->postDirectionCode; }
		public function getPostDirection()
		{
			if (!$this->postDirection) { $this->postDirection = new Direction($this->postDirectionCode); }
			return $this->postDirection;
		}
		public function getStartDate() { return $this->startDate; }
		public function getEndDate() { return $this->endDate; }
		public function getNotes() { return $this->notes; }



		public function setTownID($townID)
		{
			$this->town = new Town($townID);
			$this->townID = $townID;
		}
		public function setTown($town)
		{
			$this->town = $town;
			$this->townID = $town->getTownID();
		}
		public function setDirectionCode($char)
		{
			$this->direction = new Direction($char);
			$this->directionCode = $char;
		}
		public function setDirection($direction)
		{
			$this->direction = $direction;
			$this->directionCode = $direction->getDirectionCode();
		}
		public function setName($string) { $this->name = $string; }
		public function setSuffixAbbreviation($string)
		{
			$this->suffix = new Suffix(sanitizeString($string));
			$this->suffixAbbreviation = $string;
		}
		public function setSuffix($suffix)
		{
			$this->suffix = $suffix;
			$this->suffixAbbreviation = $suffix->getSuffixAbbreviation();
		}
		public function setPostDirectionCode($char)
		{
			$this->postDirection = new Direction($char);
			$this->postDirectionCode = $char;
		}
		public function setPostDirection($direction)
		{
			$this->postDirection = $direction;
			$this->postDirectionCode = $direction->getDirectionCode();
		}
		public function setStartDate($date)
		{
			if ($date)
			{
				if (!isValidDate($date)) { throw new Exception("invalidStartDate"); }
				$this->startDate = $date;
			}
			else { $this->startDate = ""; }
		}
		public function setEndDate($date)
		{
			if ($date)
			{
				if (!isValidDate($date)) { throw new Exception("invalidEndDate"); }
				$this->endDate = $date;
			}
			else { $this->endDate = ""; }
		}
		public function setNotes($string) { $this->notes = $string; }
	}
?>