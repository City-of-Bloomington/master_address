<?php
	class Annexation extends ActiveRecord
	{
		private $id;
		private $ordinanceNumber;
		private $name;



		public function __construct($id=null)
		{
			global $PDO;

			if ($id)
			{
				$sql = "select * from annexations where id=$id";
				$result = $PDO->query($sql);
				if ($result)
				{
					if ($row = $result->fetch())
					{
						$this->id = $id;
						$this->ordinanceNumber = $row['ordinanceNumber'];
						if ($row['name']) { $this->name = $row['name']; }


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
			if (!$this->ordinanceNumber) { throw new Exception("missingRequiredFields"); }


			$fields = array();
			$fields[] = "ordinanceNumber='{$this->ordinanceNumber}'";
			if ($this->name) { $fields[] = "name='{$this->name}'"; } else { $fields[] = "name=null"; }
			$fields = implode(",",$fields);


			if ($this->id) { $this->update($fields); }
			else { $this->insert($fields); }
		}

		private function update($fields)
		{
			global $PDO;

			$sql = "update annexations set $fields where id={$this->id}";
			if (false === $PDO->exec($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
		}

		private function insert($fields)
		{
			global $PDO;

			$sql = "insert annexations set $fields";
			if (false === $PDO->exec($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
			$this->id = $PDO->lastInsertID();
		}


		public function getId() { return $this->id; }
		public function getOrdinanceNumber() { return $this->ordinanceNumber; }
		public function getName() { return $this->name; }


		public function setOrdinanceNumber($string) { $this->ordinanceNumber = $this->sanitizeString($string); }
		public function setName($string) { $this->name = $this->sanitizeString($string); }
	}
?>