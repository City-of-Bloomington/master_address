<?php
	require_once(APPLICATION_HOME."/classes/DistrictType.inc");
	class District
	{
		private $districtID;
		private $name;
		private $districtTypeID;

		private $districtType;	# A variable to cache the type once it's looked up



		public function __construct($districtID=null)
		{
			global $PDO;

			if ($districtID)
			{
				$sql = "select * from districts where districtID=$districtID";
				$result = $PDO->query($sql);
				if ($result)
				{
					if ($row = $result->fetch())
					{
						$this->districtID = $districtID;
						$this->name = $row['name'];
						$this->districtTypeID = $row['districtTypeID'];

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
			if (!$this->name || !$this->districtTypeID) { throw new Exception("missingRequiredFields"); }


			# This generates generic SQL that should work right away.
			# You can (and maybe should) replace this $fields code with your own custom SQL
			# for each propert of this class,
			$fields = array();
			$fields[] = "name='{$this->name}'";
			$fields[] = "districtTypeID={$this->districtTypeID}";
			$fields = implode(",",$fields);


			if ($this->districtID) { $this->update($fields); }
			else { $this->insert($fields); }
		}

		private function update($fields)
		{
			global $PDO;

			$sql = "update districts set $fields where districtID={$this->districtID}";
			if (false === $PDO->exec($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
		}

		private function insert($fields)
		{
			global $PDO;

			$sql = "insert districts set $fields";
			if (false === $PDO->exec($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
			$this->districtID = $PDO->lastInsertID();
		}


		public function getDistrictID() { return $this->districtID; }
		public function getName() { return $this->name; }
		public function getDistrictTypeID() { return $this->districtTypeID; }
		public function getDistrictType()
		{
			if (!$this->districtType) { $this->districtType = new DistrictType($this->districtTypeID); }
			return $this->districtType;
		}


		public function setDistrictID($int) { $this->districtID = $int; }
		public function setName($string) { $this->name = sanitizeString($string); }
		public function setDistrictTypeID($int)
		{
			$this->districtType = new DistrictType($int);
			$this->districtTypeID = $int;
		}

	}
?>