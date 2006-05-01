<?php
	class Annexation
	{
		private $ordinanceNumber;
		private $name;



		public function __construct($ordinanceNumber=null)
		{
			global $PDO;

			if ($ordinanceNumber)
			{
				$sql = "select * from annexations where ordinanceNumber='$ordinanceNumber'";
				$result = $PDO->query($sql);
				if ($result)
				{
					if ($row = $result->fetch())
					{
						$this->ordinanceNumber = $ordinanceNumber;
						$this->name = $row['name'];

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
			if (!$this->ordinanceNumber || !$this->name) { throw new Exception("missingRequiredFields"); }

			if ($this->ordinanceNumber) { $this->update(); }
			else { $this->insert(); }
		}

		private function update()
		{
			global $PDO;

			$sql = "update annexations set name='{$this->name}' where ordinanceNumber='{$this->ordinanceNumber}'";
			if (false === $PDO->exec($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
		}

		private function insert()
		{
			global $PDO;

			$sql = "insert annexations set values('{$this->ordinanceNumber}','{$this->name}')";
			if (false === $PDO->exec($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
			$this->ordinanceNumber = $PDO->lastInsertID();
		}


		public function getOrdinanceNumber() { return $this->ordinanceNumber; }
		public function getName() { return $this->name; }


		public function setOrdinanceNumber($string) { $this->ordinanceNumber = sanitizeString($string); }
		public function setName($string) { $this->name = sanitizeString($string); }
	}
?>