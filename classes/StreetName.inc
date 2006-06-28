<?php
	class StreetName extends ActiveRecord
	{
		private $id;
		private $street_id;
		private $name_id;
		private $streetNameType_id;

		private $street;
		private $name;
		private $streetNameType;

		public function __construct(PDO $PDO,$id=null)
		{
			$this->PDO = $PDO;

			if ($id)
			{
				$sql = "select * from streetNames where id=$id";
				$result = $this->PDO->query($sql);
				if ($result)
				{
					if ($row = $result->fetch())
					{
						$this->id = $id;
						$this->street_id = $row['street_id'];
						$this->name_id = $row['name_id'];
						$this->streetNameType_id = $row['streetNameType_id'];

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
			if (!$this->street_id || !$this->name_id || !$this->streetNameType_id) { throw new Exception("missingRequiredFields"); }


			$fields = array();
			$fields[] = $this->street_id ? "street_id={$this->street_id}" : "street_id=null";
			$fields[] = $this->name_id ? "name_id={$this->name_id}" : "name_id=null";
			$fields[] = $this->streetNameType_id ? "streetNameType_id={$this->streetNameType_id}" : "streetNameType_id=null";

			$fields = implode(",",$fields);


			if ($this->id) { $this->update($fields); }
			else { $this->insert($fields); }
		}

		private function update($fields)
		{
			$sql = "update streetNames set $fields where id={$this->id}";
			if (false === $this->PDO->exec($sql)) { $e = $this->PDO->errorInfo(); throw new Exception($sql.$e[2]); }
		}

		private function insert($fields)
		{
			$sql = "insert streetNames set $fields";
			if (false === $this->PDO->exec($sql)) { $e = $this->PDO->errorInfo(); throw new Exception($sql.$e[2]); }
			$this->id = $this->PDO->lastInsertID();
		}


		public function __toString()
		{
			return $this->getFullname();
		}

		public function getId() { return $this->id; }
		public function getStreet_id() { return $this->street_id; }
		public function getName_id() { return $this->name_id; }
		public function getStreetNameType_id() { return $this->streetNameType_id; }
		public function getStreet()
		{
			if ($this->street_id)
			{
				if (!$this->street) { $this->street = new Street($this->PDO,$this->street_id); }
				return $this->street;
			}
			else return null;
		}
		public function getName()
		{
			if ($this->name_id)
			{
				if (!$this->name) { $this->name = new Name($this->PDO,$this->name_id); }
				return $this->name;
			}
			else return null;
		}
		public function getFullname() { return $this->getName()->getFullname(); }
		public function getStreetNameType()
		{
			if ($this->streetNameType_id)
			{
				if (!$this->streetNameType) { $this->streetNameType = new StreetNameType($this->PDO,$this->streetNameType_id); }
				return $this->streetNameType;
			}
			else return null;
		}
		public function getType() { return $this->getStreetNameType()->getType(); }


		public function setStreet_id($int) { $this->street = new Street($this->PDO,$int); $this->street_id = $int; }
		public function setName_id($int) { $this->name = new Name($this->PDO,$int); $this->name_id = $int; }
		public function setStreetNameType_id($int) { $this->streetNameType = new StreetNameType($this->PDO,$int); $this->streetNameType_id = $int; }
		public function setStreet($street) { $this->street = $street; $this->street_id = $street->getId(); }
		public function setName($name) { $this->name = $name; $this->name_id = $name->getId(); }
		public function setStreetNameType($type) { $this->streetNameType = $type; $this->streetNameType_id = $type->getId(); }
	}
?>