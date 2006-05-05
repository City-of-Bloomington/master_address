<?php
	class PlatType
	{
		private $type;
		private $description;



		public function __construct($type=null)
		{
			global $PDO;

			if ($type)
			{
				$sql = "select * from platTypes where type='$type'";
				$result = $PDO->query($sql);
				if ($result)
				{
					if ($row = $result->fetch())
					{
						$this->type = $type;
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
			if (!$this->type || !$this->description) { throw new Exception("missingRequiredFields"); }


			if ($this->type) { $this->update(); }
			else { $this->insert(); }
		}

		private function update()
		{
			global $PDO;

			$sql = "update platTypes set description='{$this->description}' where type='{$this->type}'";
			if (false === $PDO->exec($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
		}

		private function insert()
		{
			global $PDO;

			$sql = "insert platTypes values('{$this->type}','{$this->description}')";
			if (false === $PDO->exec($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
		}


		public function getType() { return $this->type; }
		public function getDescription() { return $this->description; }


		public function setType($char) { $this->type = ereg_replace("[^A-Z]","",strtoupper($char)); }
		public function setDescription($string) { $this->description = sanitizeString($string); }
	}
?>