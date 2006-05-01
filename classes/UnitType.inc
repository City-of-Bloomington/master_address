<?php
	class UnitType
	{
		private $unitType;
		private $description;



		public function __construct($unitType=null)
		{
			global $PDO;

			if ($unitType)
			{
				$sql = "select * from unitTypes where unitType='$unitType'";
				$result = $PDO->query($sql);
				if ($result)
				{
					if ($row = $result->fetch())
					{
						$this->unitType = $unitType;
						$this->description = $row['description'];

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
			if (!$this->unitType || !$this->description) { throw new Exception("missingRequiredFields"); }


			if ($this->unitType) { $this->update(); }
			else { $this->insert(); }
		}

		private function update()
		{
			global $PDO;

			$sql = "update unitTypes set description='{$this->description}' where unitType='{$this->unitType}'";
			if (false === $PDO->exec($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
		}

		private function insert()
		{
			global $PDO;

			$sql = "insert unitTypes set unitType='{$this->unitType}',description='{$this->description}'";
			if (false === $PDO->exec($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
		}


		public function getUnitType() { return $this->unitType; }
		public function getDescription() { return $this->description; }


		public function setUnitType($string) { $this->unitType = sanitizeString($string); }
		public function setDescription($string) { $this->description = sanitizeString($string); }
	}
?>