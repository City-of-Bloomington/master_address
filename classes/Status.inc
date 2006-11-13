<?php
/**
* @copyright Copyright (C) 2006 City of Bloomington, Indiana. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
*/
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

			if (is_numeric($id)) { $sql = "select * from {$this->tableName} where id=?"; }
			else { $sql = "select * from {$this->tableName} where status=?"; }

			try
			{
				$query = $PDO->prepare($sql);
				$query->execute(array($id));
			}
			catch (Exception $e) { throw $e; }

			$result = $query->fetchAll();
			foreach($result[0] as $field=>$value) { if ($value) $this->$field = $value; }
		}


		public function save()
		{
			# Check for required fields here.  Throw an exception if anything is missing.
			if (!$this->status) { throw new Exception("missingRequiredFields"); }


			$fields = array();
			$fields['status'] = $this->status ? $this->status : null;

			# Split the fields up into a preparedFields array and a values array.
			# PDO->execute cannot take an associative array for values, so we have
			# to strip out the keys from $fields
			$preparedFields = array();
			foreach($fields as $key=>$value)
			{
				$preparedFields[] = "$key=?";
				$values[] = $value;
			}
			$preparedFields = implode(",",$preparedFields);


			if ($this->id) { $this->update($values,$preparedFields); }
			else { $this->insert($values,$preparedFields); }
		}

		private function update($fields)
		{
			global $PDO;

			$sql = "update roles set {$this->tableName} where id={$this->id}";
			if (false === $query = $PDO->prepare($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
			if (false === $query->execute($values)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
		}

		private function insert($values,$preparedFields)
		{
			global $PDO;

			$sql = "insert {$this->tableName} set $preparedFields";
			if (false === $query = $PDO->prepare($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
			if (false === $query->execute($values)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
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