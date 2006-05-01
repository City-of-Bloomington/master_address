<?php
	class Direction
	{
		private $directionCode;
		private $direction;



		public function __construct($directionCode=null)
		{
			global $PDO;

			if ($directionCode)
			{
				$sql = "select * from directions where directionCode='$directionCode'";
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
			if (!$this->directionCode || !$this->direction) { throw new Exception("missingRequiredFields"); }


			if ($this->directionCode) { $this->update(); }
			else { $this->insert(); }
		}

		private function update()
		{
			global $PDO;

			$sql = "update directions set direction='{$this->direction}' where directionCode='{$this->directionCode}'";
			if (false === $PDO->exec($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
		}

		private function insert()
		{
			global $PDO;

			$sql = "insert directions set values('{$directionCode}','{$this->direction}')";
			if (false === $PDO->exec($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
		}


		public function getDirectionCode() { return $this->directionCode; }
		public function getDirection() { return $this->direction; }


		public function setDirectionCode($char) { $this->directionCode = $char; }
		public function setDirection($string) { $this->direction = $string; }
	}
?>