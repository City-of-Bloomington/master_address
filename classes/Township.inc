<?php
	class Township extends ActiveRecord
	{
		private $id;
		private $name;
		private $abbreviation;
		private $quarterCode;



		public function __construct($id=null)
		{
			global $PDO;

			if ($id)
			{
				$sql = "select * from townships where id=$id";
				$result = $PDO->query($sql);
				if ($result)
				{
					if ($row = $result->fetch())
					{
						$this->id = $id;
						$this->name = $row['name'];
						$this->abbreviation = $row['abbreviation'];
						$this->quarterCode = $row['quarterCode'];

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
			if (!$this->name || !$this->abbreviation) { throw new Exception("missingRequiredFields"); }

			$fields = array();
			$fields[] = "name='{$this->name}'";
			$fields[] = "abbreviation='{$this->abbreviation}'";
			if ($this->quarterCode) { $fields[] = "quarterCode='{$this->quarterCode}'"; } else { $fields[] = "quarterCode=null"; }
			$fields = implode(",",$fields);


			if ($this->id) { $this->update($fields); }
			else { $this->insert($fields); }
		}

		private function update($fields)
		{
			global $PDO;

			$sql = "update townships set $fields where id={$this->id}";
			if (false === $PDO->exec($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
		}

		private function insert($fields)
		{
			global $PDO;

			$sql = "insert townships set $fields";
			if (false === $PDO->exec($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
			$this->id = $PDO->lastInsertID();
		}

		public function __toString() { return $this->name; }


		public function getId() { return $this->id; }
		public function getName() { return $this->name; }
		public function getAbbreviation() { return $this->abbreviation; }
		public function getQuarterCode() { return $this->quarterCode; }


		public function setName($string) { $this->name = trim($string); }
		public function setAbbreviation($char) { $this->abbreviation = ereg_replace("[^A-Z]","",strtoupper($char)); }
		public function setQuarterCode($char) { $this->quarterCode = ereg_replace("[^A-Z]","",strtoupper($char)); }
	}
?>