<?php
	class Status extends ActiveRecord
	{
		private $id;
		private $status;

		private $type;
		private $tableName;

		public function __construct($type,$id=1)
		{
			global $PDO;

			$this->type = $type;
			$this->tableName = $type."Statuses";

			if (is_numeric($id)) { $sql = "select * from {$this->tableName} where id=$id"; }
			else { $sql = "select * from {$this->tableName} where status='$id'"; }

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

			$sql = "update {$this->tableName} set $fields where id={$this->id}";
			if (false === $PDO->exec($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
		}

		private function insert($fields)
		{
			global $PDO;

			$sql = "insert {$this->tableName} set $fields";
			if (false === $PDO->exec($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
			$this->id = $PDO->lastInsertID();
		}


		public function getId() { return $this->id; }
		public function getStatus() { return $this->status; }
		public function getType() { return $this->type; }
		public function getTableName() { return $this->tableName; }

		public function __toString() { return $this->status; }

		public function setStatus($string) { $this->status = trim($string); }
	}
?>