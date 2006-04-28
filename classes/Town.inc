<?php
	class Town
	{
		private $townID;
		private $name;



		public function __construct($townID=null)
		{
			global $PDO;

			if ($townID)
			{
				$sql = "select * from towns where townID=$townID";
				$result = $PDO->query($sql);
				if ($row = $result->fetch())
				{
					$this->townID = $townID;
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
			if (!$this->name) { throw new Exception("missingRequiredFields"); }

			if ($this->townID) { $this->update(); }
			else { $this->insert(); }
		}

		private function update()
		{
			global $PDO;

			$sql = "update towns set name='{$this->name}' where townID={$this->townID}";
			$PDO->exec($sql);
		}

		private function insert()
		{
			global $PDO;

			$sql = "insert towns set name='{$this->name}'";
			$PDO->exec($sql);
			$this->townID = $PDO->lastInsertID();
		}


		public function getTownID() { return $this->townID; }
		public function getName() { return $this->name; }


		public function setName($string) { $this->name = sanitizeString($string); }

	}
?>