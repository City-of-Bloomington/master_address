<?php
	require_once(APPLICATION_HOME."/classes/DistrictType.inc");
	class District
	{
		private $id;
		private $name;
		private $districtType_id;

		private $districtType;


		public function __construct($id=null)
		{
			global $PDO;

			if ($id)
			{
				$sql = "select * from districts where id=$id";
				$result = $PDO->query($sql);
				if ($result)
				{
					if ($row = $result->fetch())
					{
						$this->id = $id;
						$this->name = $row['name'];
						$this->districtType_id = $row['districtType_id'];

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
			if (!$this->name || !$this->districtType_id) { throw new Exception("missingRequiredFields"); }


			# This generates generic SQL that should work right away.
			# You can (and maybe should) replace this $fields code with your own custom SQL
			# for each property of this class,
			$fields = array();
			$fields[] = "name='{$this->name}'";
			$fields[] = "districtType_id='{$this->districtType_id}'";
			$fields = implode(",",$fields);


			if ($this->id) { $this->update($fields); }
			else { $this->insert($fields); }
		}

		private function update($fields)
		{
			global $PDO;

			$sql = "update districts set $fields where id={$this->id}";
			if (false === $PDO->exec($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
		}

		private function insert($fields)
		{
			global $PDO;

			$sql = "insert districts set $fields";
			if (false === $PDO->exec($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
			$this->id = $PDO->lastInsertID();
		}


		public function getId() { return $this->id; }
		public function getName() { return $this->name; }
		public function getDistrictType_id() { return $this->districtType_id; }

		public function getDistrictType()
		{
			if (!$this->districtType) { $this->districtType = new DistrictType($this->districtType_id); }
			return $this->districtType;
		}


		public function setName($string) { $this->name = sanitizeString($string); }
		public function setDistrictType_id($districtType_id)
		{
			$this->districtType = new DistrictType($districtType_id);
			$this->districtType_id = $districtType_id;
		}

	}
?>