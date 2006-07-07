<?php
	class Suffix extends ActiveRecord
	{
		private $id;
		private $suffix;
		private $description;



		public function __construct($id=null)
		{
			global $PDO;

			if ($id)
			{
				$sql = "select * from suffixes where id=$id";
				$result = $PDO->query($sql);
				if ($result)
				{
					if ($row = $result->fetch())
					{
						$this->id = $id;
						$this->suffix = $row['suffix'];
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
			if (!$this->suffix || !$this->description) { throw new Exception("missingRequiredFields"); }


			$fields = array();
			$fields[] = "suffix='{$this->suffix}'";
			$fields[] = "description='{$this->description}'";
			$fields = implode(",",$fields);


			if ($this->id) { $this->update($fields); }
			else { $this->insert($fields); }
		}

		private function update($fields)
		{
			global $PDO;

			$sql = "update suffixes set $fields where id={$this->id}";
			if (false === $PDO->exec($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
		}

		private function insert($fields)
		{
			global $PDO;

			$sql = "insert suffixes set $fields";
			if (false === $PDO->exec($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
			$this->id = $PDO->lastInsertID();
		}


		public function getId() { return $this->id; }
		public function getSuffix() { return $this->suffix; }
		public function getDescription() { return $this->description; }


		public function setSuffix($char) { $this->suffix = ereg_replace("[^A-Z]","",strtoupper($char)); }
		public function setDescription($string) { $this->description = $this->sanitizeString($string); }
	}
?>