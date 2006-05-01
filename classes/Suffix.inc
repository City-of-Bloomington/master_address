<?php
	class Suffix
	{
		private $suffix;
		private $description;



		public function __construct($suffix=null)
		{
			global $PDO;

			if ($suffix)
			{
 				$sql = "select * from suffixes where suffix='$suffix'";
				$result = $PDO->query($sql);
				if ($result)
				{
					if ($row = $result->fetch())
					{
						$this->suffix = $suffix;
						$this->description = $row['description'];

						$result->closeCursor();
					}
					else { throw new Exception($sql); }
				}
				else { $e=$PDO->errorInfo(); throw new Exception($sql.$e[2]); }
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
			if (!$this->suffix || !$this->description) { throw new Exception("missingRequiredFields"); }


			if ($this->suffix) { $this->update(); }
			else { $this->insert(); }
		}

		private function update()
		{
			global $PDO;

			$sql = "update suffixes set description='{$this->description}' where suffix='{$this->suffix}'";
			if (false === $PDO->exec($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
		}

		private function insert()
		{
			global $PDO;

			$sql = "insert suffixes set suffix='{$this->suffix}',description='{$this->description}'";
			if (false === $PDO->exec($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
			$this->suffix = $PDO->lastInsertID();
		}


		public function getSuffix() { return $this->suffix; }
		public function getDescription() { return $this->description; }


		public function setSuffix($string) { $this->suffix = sanitizeString($string); }
		public function setDescription($string) { $this->description = sanitizeString($string); }
	}
?>