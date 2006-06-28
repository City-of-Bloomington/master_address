<?php
	class Status extends ActiveRecord
	{
		private $id;
		private $status;

		public function __construct(PDO $PDO,$id=null)
		{
			$this->PDO = $PDO;

			if ($id)
			{
				$sql = "select * from statuses where id=$id";
				$result = $this->PDO->query($sql);
				if ($result)
				{
					if ($row = $result->fetch())
					{
						$this->id = $id;
						$this->status = $row['status'];

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
			if (!$this->status) { throw new Exception("missingRequiredFields"); }


			$fields = array();
			$fields[] = "status='{$this->status}'";
			$fields = implode(",",$fields);


			if ($this->id) { $this->update($fields); }
			else { $this->insert($fields); }
		}

		private function update($fields)
		{
			$sql = "update statuses set $fields where id={$this->id}";
			if (false === $this->PDO->exec($sql)) { $e = $this->PDO->errorInfo(); throw new Exception($sql.$e[2]); }
		}

		private function insert($fields)
		{
			$sql = "insert statuses set $fields";
			if (false === $this->PDO->exec($sql)) { $e = $this->PDO->errorInfo(); throw new Exception($sql.$e[2]); }
			$this->id = $this->PDO->lastInsertID();
		}


		public function getId() { return $this->id; }
		public function getStatus() { return $this->status; }

		public function __toString() { return $this->status; }

		public function setStatus($string) { $this->status = sanitizeString($string); }
	}
?>