<?php
	class StreetNameType extends ActiveRecord
	{
		private $id;
		private $type;
		private $description;

		public function __construct(PDO $PDO,$id=null)
		{
			$this->PDO = $PDO;

			if ($id)
			{
				if (is_numeric($id)) { $sql = "select * from streetNameTypes where id=$id"; }
				else { $sql = "select * from streetNameTypes where type='$id'"; }

				$result = $this->PDO->query($sql);
				if ($result)
				{
					if ($row = $result->fetch())
					{
						$this->id = $row['id'];
						$this->type = $row['type'];
						$this->description = $row['description'];

						$result->closeCursor();
					}
					else { throw new Exception($sql); }
				}
				else { $e = $this->PDO->errorInfo(); throw new Exception($sql.$e[2]); }
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
			if (!$this->type) { throw new Exception("missingRequiredFields"); }


			$fields = array();
			$fields[] = $this->type ? "type='{$this->type}'" : "type=null";
			$fields[] = $this->description ? "description='{$this->description}'" : "description=null";
			$fields = implode(",",$fields);


			if ($this->id) { $this->update($fields); }
			else { $this->insert($fields); }
		}

		private function update($fields)
		{
			$sql = "update streetNameTypes set $fields where id={$this->id}";
			if (false === $this->PDO->exec($sql)) { $e = $this->PDO->errorInfo(); throw new Exception($sql.$e[2]); }
		}

		private function insert($fields)
		{
			$sql = "insert streetNameTypes set $fields";
			if (false === $this->PDO->exec($sql)) { $e = $this->PDO->errorInfo(); throw new Exception($sql.$e[2]); }
			$this->id = $this->PDO->lastInsertID();
		}


		public function __toString() { return $this->type; }

		public function getId() { return $this->id; }
		public function getType() { return $this->type; }
		public function getDescription() { return $this->description; }

		public function setType($string) { $this->type = sanitizeString($string); }
		public function setDescription($string) { $this->description = sanitizeString($string); }
	}
?>