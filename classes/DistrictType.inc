<?php
	class DistrictType
	{
		private $districtTypeID;
		private $type;



		public function __construct($districtTypeID=null)
		{
			global $PDO;

			if ($districtTypeID)
			{
				$sql = "select * from districtTypes where districtTypeID=$districtTypeID";
				$result = $PDO->query($sql);
				if ($result)
				{
					if ($row = $result->fetch())
					{
						$this->districtTypeID = $districtTypeID;
						$this->type = $row['type'];

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
			# Check for required properties here.  Throw an exception if anything is missing.
			if (!$this->type) { throw new Exception("missingRequiredFields"); }

			if ($this->districtTypeID) { $this->update(); }
			else { $this->insert(); }
		}

		private function update()
		{
			global $PDO;

			$sql = "update districtTypes set type='{$this->type}' where districtTypeID={$this->districtTypeID}";
			if (false === $PDO->exec($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
		}

		private function insert()
		{
			global $PDO;

			$sql = "insert districtTypes set type='{$this->type}'";
			if (false === $PDO->exec($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
			$this->districtTypeID = $PDO->lastInsertID();
		}


		public function getDistrictTypeID() { return $this->districtTypeID; }
		public function getType() { return $this->type; }


		public function setType($string) { $this->type = sanitizeString($string); }
	}
?>