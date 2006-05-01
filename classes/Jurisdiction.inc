<?php
	class Jurisdiction
	{
		private $jurisdictionID;
		private $name;



		public function __construct($jurisdictionID=null)
		{
			global $PDO;

			if ($jurisdictionID)
			{
				$sql = "select * from jurisdictions where jurisdictionID=$jurisdictionID";
				$result = $PDO->query($sql);
				if ($row = $result->fetch())
				{
					# This will load all fields in the table as properties of this class.
					# You may want to replace this with, or add your own extra, custom loading
					$this->jurisdictionID = $jurisdictionID;
					$this->name = $row['name'];


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
			# Check for required fields here.  Throw an exception if anything is missing.
			if (!$this->name) { throw new Exception("missingRequiredFields"); }

			if ($this->jurisdictionID) { $this->update(); }
			else { $this->insert(); }
		}

		private function update()
		{
			global $PDO;

			$sql = "update jurisdictions set name='{$this->name}' where jurisdictionID={$this->jurisdictionID}";
			if (false === $PDO->exec($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
		}

		private function insert()
		{
			global $PDO;

			$sql = "insert jurisdictions set name='{$this->name}'";
			if (false === $PDO->exec($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
			$this->jurisdictionID = $PDO->lastInsertID();
		}


		public function getJurisdictionID() { return $this->jurisdictionID; }
		public function getName() { return $this->name; }


		public function setJurisdictionID($int) { $this->jurisdictionID = $int; }
		public function setName($string) { $this->name = sanitizeString($string); }

	}
?>