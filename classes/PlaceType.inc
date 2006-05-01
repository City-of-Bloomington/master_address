<?php
	class PlaceType
	{
		private $placeTypeID;
		private $type;
		private $description;



		public function __construct($placeTypeID=null)
		{
			global $PDO;

			if ($placeTypeID)
			{
				$sql = "select * from placeTypes where placeTypeID=$placeTypeID";
				$result = $PDO->query($sql);
				if ($result)
				{
					if ($row = $result->fetch())
					{
						# This will load all fields in the table as properties of this class.
						# You may want to replace this with, or add your own extra, custom loading
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
			if (!$this->type || !$this->description) { throw new Exception("missingRequiredFields"); }


			$fields = array();
			$fields[] = "type='{$this->type}'";
			$fields[] = "description='{$this->description}'";
			$fields = implode(",",$fields);


			if ($this->placeTypeID) { $this->update($fields); }
			else { $this->insert($fields); }
		}

		private function update($fields)
		{
			global $PDO;

			$sql = "update placeTypes set $fields where placeTypeID={$this->placeTypeID}";
			if (false === $PDO->exec($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
		}

		private function insert($fields)
		{
			global $PDO;

			$sql = "insert placeTypes set $fields";
			if (false === $PDO->exec($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
			$this->placeTypeID = $PDO->lastInsertID();
		}


		public function getPlaceTypeID() { return $this->placeTypeID; }
		public function getType() { return $this->type; }
		public function getDescription() { return $this->description; }


		public function setType($string) { $this->type = sanitizeString($string); }
		public function setDescription($string) { $this->description = sanitizeString($string); }
	}
?>