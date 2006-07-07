<?php
	class TrashPickupDay extends ActiveRecord
	{
		private $id;
		private $day;

		private $daysOfWeek = array("Sunday","Monday","Tueday","Wednesday","Thursday","Friday","Saturday");



		public function __construct($id=null)
		{
			global $PDO;

			if ($id)
			{
				$sql = "select * from trashPickupDays where id=$id";
				$result = $PDO->query($sql);
				if ($result)
				{
					if ($row = $result->fetch())
					{
						$this->id = $id;
						$this->day = $row['day'];

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
			if (!$this->day) { throw new Exception("missingRequiredFields"); }


			$fields = array();
			$fields[] = "day='{$this->day}'";
			$fields = implode(",",$fields);


			if ($this->id) { $this->update($fields); }
			else { $this->insert($fields); }
		}

		private function update($fields)
		{
			global $PDO;

			$sql = "update trashPickupDays set $fields where id={$this->id}";
			if (false === $PDO->exec($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
		}

		private function insert($fields)
		{
			global $PDO;

			$sql = "insert trashPickupDays set $fields";
			if (false === $PDO->exec($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
			$this->id = $PDO->lastInsertID();
		}


		public function getId() { return $this->id; }
		public function getDay() { return $this->day; }

		public function __toString() { return $this->day; }


		public function setDay($string)
		{
			if (in_array($string,$this->daysOfWeek)) { $this->day = $string; }
		}
	}
?>