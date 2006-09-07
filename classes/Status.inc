<?php
	class Status extends ActiveRecord
	{
		private $id;
		private $status;



		public function __construct($id=1)
		{
			global $PDO;

			if (is_numeric($id)) { $sql = "select * from statuses where id=$id"; }
			else { $sql = "select * from statuses where status='$id'"; }

			$result = $PDO->query($sql);
			if ($result)
			{
				if ($row = $result->fetch())
				{
					$this->id = $row['id'];
					$this->status = $row['status'];

					$result->closeCursor();
				}
				else { throw new Exception($sql); }
			}
			else { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
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
			global $PDO;

			$sql = "update statuses set $fields where id={$this->id}";
			if (false === $PDO->exec($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
		}

		private function insert($fields)
		{
			global $PDO;

			$sql = "insert statuses set $fields";
			if (false === $PDO->exec($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
			$this->id = $PDO->lastInsertID();
		}


		public function getId() { return $this->id; }
		public function getStatus() { return $this->status; }

		public function __toString() { return $this->status; }

		public function setStatus($string) { $this->status = trim($string); }
	}
?>