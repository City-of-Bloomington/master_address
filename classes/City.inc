<?php
/**
* @copyright Copyright (C) 2006 City of Bloomington, Indiana. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
*/
	class City extends ActiveRecord
	{
		private $id;
		private $name;
		private $zip;

		public function __construct($id=null)
		{
			global $PDO;

			if ($id)
			{
				if (is_numeric($id)) { $sql = "select * from cities where id=$id"; }
				else { $sql = "select * from cities where name='$id'"; }

				$result = $PDO->query($sql);
				if ($result)
				{
					if ($row = $result->fetch())
					{
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
			if (!$this->name) { throw new Exception("missingRequiredFields"); }


			$fields = array();
			$fields[] = $this->name ? "name='{$this->name}'" : "name=null";
			$fields[] = $this->zip ? "zip={$this->zip}" : "zip=null";
			$fields = implode(",",$fields);


			if ($this->id) { $this->update($fields); }
			else { $this->insert($fields); }
		}

		private function update($fields)
		{
			global $PDO;

			$sql = "update cities set $fields where id={$this->id}";
			if (false === $PDO->exec($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
		}

		private function insert($fields)
		{
			global $PDO;

			$sql = "insert cities set $fields";
			if (false === $PDO->exec($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
			$this->id = $PDO->lastInsertID();
		}


		public function getId() { return $this->id; }
		public function getName() { return $this->name; }
		public function getZip() { return $this->zip; }

		public function __toString() { return $this->name; }


		public function setName($string) { $this->name = trim($string); }
		public function setZip($int) { $this->zip = ereg_replace("[^0-9]","",$int); }
	}
?>