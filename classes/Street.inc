<?php
	class Street
	{
		private $id;
		private $notes;
		private $status_id;

		private $status;
		private $segmentList;

		private $streetName;
		private $streetNameList;

		public function __construct($id=null)
		{
			global $PDO;

			if ($id)
			{
				$sql = "select * from streets where id=$id";
				$result = $PDO->query($sql);
				if ($result)
				{
					if ($row = $result->fetch())
					{
						$this->id = $id;
						$this->notes = $row['notes'];
						$this->status_id = $row['status_id'];

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


			$fields = array();
			if ($this->notes) { $fields[] = "notes='{$this->notes}'"; } else { $fields[] = "notes=null"; }
			if ($this->status_id) { $fields[] = "status_id={$this->status_id}"; } else { $fields[] = "status_id=null"; }
			$fields = implode(",",$fields);


			if ($this->id) { $this->update($fields); }
			else { $this->insert($fields); }
		}

		private function update($fields)
		{
			global $PDO;

			$sql = "update streets set $fields where id={$this->id}";
			if (false === $PDO->exec($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
		}

		private function insert($fields)
		{
			global $PDO;

			$sql = "insert streets set $fields";
			if (false === $PDO->exec($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
			$this->id = $PDO->lastInsertID();
		}


		public function getId() { return $this->id; }
		public function getNotes() { return $this->notes; }
		public function getStatus_id() { return $this->status_id; }
		public function getStatus()
		{
			if ($this->status_id)
			{
				if (!$this->status) { $this->status = new Status($this->status_id); }
				return $this->status;
			}
			else return null;
		}
		public function getSegmentList()
		{
			if (!$this->segmentList) { $this->segmentList = new SegmentList(array("street_id"=>$this->id)); }
			return $this->segmentList;
		}
		public function getSegments() { return $this->getSegmentList(); }
		/*
		* Returns the primary name for this street
		*/
		public function getStreetName()
		{
			if (!$this->streetName)
			{
				foreach($this->getStreetNames() as $streetName)
				{
					if ($streetName->getStreetNameType()->getType() == "STREET") { $this->streetName = $streetName; }
				}
			}
			return $this->streetName;
		}
		public function getFullStreetName()
		{
			if ($this->getStreetName())
			{
				return $this->getStreetName()->getName()->getFullName();
			}
			else return "";
		}
		public function getStreetNames()
		{
			if (!count($this->streetNameList)) { $this->streetNameList = new StreetNameList(array("street_id"=>$this->id)); }
			return $this->streetNameList;
		}


		public function __toString() { return $this->getStreetName()->getName()->getFullname(); }

		public function setNotes($string) { $this->notes = sanitizeString($string); }
		public function setStatus_id($int) { $this->status = new Status($int); $this->status_id = $int; }
	}
?>