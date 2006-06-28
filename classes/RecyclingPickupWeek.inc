<?php
	class RecyclingPickupWeek extends ActiveRecord
	{
		private $id;
		private $week;

		public function __construct(PDO $PDO,$id=null)
		{
			$this->PDO = $PDO;

			if ($id)
			{
				$sql = "select * from recyclingPickupWeeks where id=$id";
				$result = $this->PDO->query($sql);
				if ($result)
				{
					if ($row = $result->fetch())
					{
						$this->id = $id;
						$this->week = $row['week'];

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
			if (!$this->week) { throw new Exception("missingRequiredFields"); }


			$fields = array();
			$fields[] = "week='{$this->week}'";
			$fields = implode(",",$fields);


			if ($this->id) { $this->update($fields); }
			else { $this->insert($fields); }
		}

		private function update($fields)
		{
			$sql = "update recyclingPickupWeeks set $fields where id={$this->id}";
			if (false === $this->PDO->exec($sql)) { $e = $this->PDO->errorInfo(); throw new Exception($sql.$e[2]); }
		}

		private function insert($fields)
		{
			$sql = "insert recyclingPickupWeeks set $fields";
			if (false === $this->PDO->exec($sql)) { $e = $this->PDO->errorInfo(); throw new Exception($sql.$e[2]); }
			$this->id = $this->PDO->lastInsertID();
		}


		public function getId() { return $this->id; }
		public function getWeek() { return $this->week; }

		public function __toString() { return $this->week; }


		public function setWeek($char) { $this->week = $char; }

	}
?>