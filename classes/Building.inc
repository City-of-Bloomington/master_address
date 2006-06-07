<?php
	class Building
	{
		private $id;
		private $place_id;
		private $name;
		private $startDate;
		private $endDate;
		private $GISTag;
		private $status_id;

		private $place;
		private $status;



		public function __construct($id=null)
		{
			global $PDO;

			if ($id)
			{
				$sql = "select * from buildings where id=$id";
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
			if (!$this->place_id) { throw new Exception("missingRequiredFields"); }


			$fields = array();
			$fields[] = "place_id={$this->place_id}";
			$fields[] = $this->name ? "name='{$this->name}'" : "name=null";
			$fields[] = $this->startDate ? "startDate='{$this->startDate}'" : "startDate=now()";
			$fields[] = $this->endDate ? "endDate='{$this->endDate}'" : "endDate=null";
			$fields[] = $this->GISTag ? "GISTag='{$this->GISTag}'" : "GISTag=null";
			$fields[] = $this->status_id ? "status_id={$this->status_id}" : "status_id=null";
			$fields = implode(",",$fields);


			if ($this->id) { $this->update($fields); }
			else { $this->insert($fields); }
		}

		private function update($fields)
		{
			global $PDO;

			$sql = "update buildings set $fields where id={$this->id}";
			if (false === $PDO->exec($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
		}

		private function insert($fields)
		{
			global $PDO;

			$sql = "insert buildings set $fields";
			if (false === $PDO->exec($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
			$this->id = $PDO->lastInsertID();
		}


		public function getId() { return $this->id; }
		public function getPlace_id() { return $this->place_id; }
		public function getName() { return $this->name; }
		public function getStartDate() { return $this->startDate; }
		public function getEndDate() { return $this->endDate; }
		public function getGISTag() { return $this->GISTag; }
		public function getStatus_id() { return $this->status_id; }
		public function getPlace()
		{
			if ($this->place_id)
			{
				if (!$this->place) { $this->place = new Place($this->place_id); }
				return $this->place;
			}
			else return null;
		}
		public function getStatus()
		{
			if ($this->status_id)
			{
				if (!$this->status) { $this->status = new Status($this->status_id); }
				return $this->status;
			}
			else return null;
		}


		public function setPlace_id($int) { $this->place = new Place($int); $this->place_id = $int; }
		public function setName($string) { $this->name = sanitizeString($string); }
		public function setStartDate($date) { $this->startDate = $date; }
		public function setEndDate($date) { $this->endDate = $date; }
		public function setGISTag($string) { $this->GISTag = sanitizeString($string); }
		public function setStatus_id($int) { $this->status = new Status($int); $this->status_id = $int; }
	}
?>