<?php
	class BuildingType
	{
		private $typeID;
		private $description;



		public function __construct($typeID=null)
		{
			global $PDO;

			if ($typeID)
			{
				$sql = "select * from buildingTypes where typeID=$typeID";
				$result = $PDO->query($sql);
				if ($result)
				{
					if ($row = $result->fetch())
					{
						$this->typeID = $typeID;
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
			if (!$this->description) { throw new Exception("missingRequiredFields"); }

			if ($this->typeID) { $this->update(); }
			else { $this->insert(); }
		}

		private function update()
		{
			global $PDO;

			$sql = "update buildingTypes set description='{$this->description}' where typeID={$this->typeID}";
			if (false === $PDO->exec($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
		}

		private function insert()
		{
			global $PDO;

			$sql = "insert buildingTypes set description='{$this->description}'";
			if (false === $PDO->exec($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
			$this->typeID = $PDO->lastInsertID();
		}


		public function getTypeID() { return $this->typeID; }
		public function getDescription() { return $this->description; }


		public function setDescription($string) { $this->description = sanitizeString($string); }
	}
?>