<?php
	class Township
	{
		private $townshipID;
		private $name;
		private $abbreviation;
		private $quarterCode;



		public function __construct($townshipID=null)
		{
			global $PDO;

			if ($townshipID)
			{
				$sql = "select * from townships where townshipID=$townshipID";
				$result = $PDO->query($sql);
				if ($row = $result->fetch())
				{
					$this->townshipID = $townshipID;
					$this->name = $row['name'];
					$this->abbreviation = $row['abbreviation'];
					if ($row['quarterCode']) { $this->quarterCode = $row['quarterCode']; }

					$result->closeCursor();
				}
				else { throw new Exception($sql); }
			}
			else
			{
				# This is where the code goes to generate a new, empty instance.
				# Set any default values for properties that need it here
			}
		}


		public function save()
		{
			# Make sure we've got a name and abbreviation
			if (!$this->name || !$this->abbreviation) { throw new Exception("missingRequiredFields"); }

			$fields = array();
			$fields[] = "name='{$this->name}'";
			$fields[] = "abbreviation='{$this->abbreviation}'";
			if ($this->quarterCode) { $fields[] = "quarterCode='{$this->quarterCode}'"; } else { $fields[] = "quarterCode=null"; }
			$fields = implode(",",$fields);

			if ($this->townshipID) { $this->update($fields); }
			else { $this->insert($fields); }
		}

		private function update($fields)
		{
			global $PDO;

			$sql = "update townships set $fields where townshipID={$this->townshipID}";
			if (false === $PDO->exec($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
		}

		private function insert($fields)
		{
			global $PDO;

			$sql = "insert townships set $fields";
			if (false === $PDO->exec($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
			$this->townshipID = $PDO->lastInsertID();
		}


		public function getTownshipID() { return $this->townshipID; }
		public function getName() { return $this->name; }
		public function getAbbreviation() { return $this->abbreviation; }
		public function getQuarterCode() { return $this->quarterCode; }


		public function setTownshipID($int) { $this->townshipID = $int; }
		public function setName($string) { $this->name = sanitizeString($string); }
		public function setAbbreviation($char) { $this->abbreviation = ereg_replace("[^A-Z]","",strtoupper($char)); }
		public function setQuarterCode($char) { $this->quarterCode = ereg_replace("[^A-Z]","",strtoupper($char)); }
	}
?>