<?php
	class Name extends ActiveRecord
	{
		private $id;
		private $town_id;
		private $direction_id;
		private $name;
		private $suffix_id;
		private $postDirection_id;
		private $startDate;
		private $endDate;
		private $notes;

		private $fullname;

		private $town;
		private $direction;
		private $suffix;
		private $postDirection;

		private $streetNameList;
		private $streets = array();

		public function __construct($id=null)
		{
			global $PDO;

			if ($id)
			{
				$sql = "select * from names where id=$id";
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
				else { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]);
				}
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


			# This generates generic SQL that should work right away.
			# You can (and maybe should) replace this $fields code with your own custom SQL
			# for each property of this class,
			$fields = array();
			$fields[] = "name='{$this->name}'";
			if ($this->town_id) { $fields[] = "town_id={$this->town_id}"; } else { $fields[] = "town_id=null"; }
			if ($this->direction_id) { $fields[] = "direction_id={$this->direction_id}"; } else { $fields[] = "direction_id=null"; }
			if ($this->suffix_id) { $fields[] = "suffix_id={$this->suffix_id}"; } else { $fields[] = "suffix_id=null"; }
			if ($this->postDirection_id) { $fields[] = "postDirection_id={$this->postDirection_id}"; } else { $fields[] = "postDirection_id=null"; }
			if ($this->startDate) { $fields[] = "startDate='{$this->startDate}'"; } else { $fields[] = "startDate=null"; }
			if ($this->endDate) { $fields[] = "endDate='{$this->endDate}'"; } else { $fields[] = "endDate=null"; }
			if ($this->notes) { $fields[] = "notes='{$this->notes}'"; } else { $fields[] = "notes=null"; }
			$fields = implode(",",$fields);

			if ($this->id) { $this->update($fields); }
			else { $this->insert($fields); }
		}

		private function update($fields)
		{
			global $PDO;

			$sql = "update names set $fields where id={$this->id}";
			if (false === $PDO->exec($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
		}

		private function insert($fields)
		{
			global $PDO;

			$sql = "insert names set $fields";
			if (false === $PDO->exec($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
			$this->id = $PDO->lastInsertID();
		}

		public function __toString() { return $this->getFullname(); }

		public function getId() { return $this->id; }
		public function getTown_id() { return $this->town_id; }
		public function getDirection_id() { return $this->direction_id; }
		public function getName() { return $this->name; }
		public function getSuffix_id() { return $this->suffix_id; }
		public function getPostDirection_id() { return $this->postDirection_id; }
		public function getStartDate() { return $this->startDate; }
		public function getEndDate() { return $this->endDate; }
		public function getNotes() { return $this->notes; }
		public function getFullname()
		{
			if (!$this->fullname)
			{
				$temp = array();
				if ($this->direction_id) { $temp[] = $this->getDirection()->getCode(); }
				$temp[] = $this->name;
				if ($this->suffix_id) { $temp[] = $this->getSuffix()->getSuffix(); }
				if ($this->postDirection_id) { $temp[] = $this->getPostDirection()->getCode(); }
				$this->fullname = implode(" ",$temp);
			}
			return $this->fullname;
		}
		public function getTown()
		{
			if ($this->town_id)
			{
				if (!$this->town) { $this->town = new Town($this->town_id); }
				return $this->town;
			}
			else return null;
		}
		public function getDirection()
		{
			if ($this->direction_id)
			{
				if (!$this->direction) { $this->direction = new Direction($this->direction_id); }
				return $this->direction;
			}
			else return null;
		}
		public function getSuffix()
		{
			if ($this->suffix_id)
			{
				if (!$this->suffix) { $this->suffix = new Suffix($this->suffix_id); }
				return $this->suffix;
			}
			else return null;
		}
		public function getPostDirection()
		{
			if ($this->postDirection_id)
			{
				if (!$this->postDirection) { $this->postDirection = new Direction($this->postDirection_id); }
				return $this->postDirection;
			}
			else return null;
		}
		public function getStreetNameList()
		{
			if (!$this->streetNameList)
			{
				$this->streetNameList = new StreetNameList();
				$this->streetNameList->find(array("name_id"=>$this->id));
			}
			return $this->streetNameList;
		}
		public function getStreetNames() { return $this->getStreetNameList(); }
		public function getStreets()
		{
			if (!count($this->streets))
			{
				foreach($this->getStreetNameList() as $streetName) { $this->streets[] = $streetName->getStreet(); }
			}
			return $this->streets;
		}


		public function setTown_id($int) { $this->town = new Town($int); $this->town_id = $int; }
		public function setDirection_id($int) { $this->direction = new Direction($int); $this->direction_id = $int; }
		public function setName($string) { $this->name = trim($string); }
		public function setSuffix_id($int) { $this->suffix = new Suffix($int); $this->suffix_id = $int; }
		public function setPostDirection_id($int) { $this->postDirection = new Direction($int); $this->postDirection_id = $int; }
		public function setStartDate($date) { $this->startDate = is_array($date) ? $this->dateArrayToString($date) : $date; }
		public function setEndDate($date) { $this->endDate = is_array($date) ? $this->dateArrayToString($date) : $date; }
		public function setNotes($string) { $this->notes = trim($string); }
	}
?>