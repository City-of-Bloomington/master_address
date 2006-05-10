<?php
	require_once(APPLICATION_HOME."/classes/Place.inc");
	require_once(APPLICATION_HOME."/classes/Segment.inc");
	require_once(APPLICATION_HOME."/classes/City.inc");
	require_once(APPLICATION_HOME."/classes/Status.inc");
	class Address
	{
		private $id;
		private $place_id;
		private $segment_id;
		private $number;
		private $suffix;
		private $addressType;
		private $city_id;
		private $zip;
		private $zipplus4;
		private $status_id;
		private $active;
		private $censusBlockFIPSCode;
		private $statePlaneX;
		private $statePlaneY;
		private $latitude;
		private $longitude;
		private $startDate;
		private $endDate;
		private $notes;

		private $place;
		private $segment;
		private $city;
		private $status;



		public function __construct($id=null)
		{
			global $PDO;

			if ($id)
			{
				$sql = "select * from addresses where id=$id";
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
			if (!$this->place_id || !$this->segment_id || !$this->number || !$this->city_id) { throw new Exception("missingRequiredFields"); }


			# This generates generic SQL that should work right away.
			# You can (and maybe should) replace this $fields code with your own custom SQL
			# for each property of this class,
			$fields = array();
			$fields[] = "place_id={$this->place_id}";
			$fields[] = "segment_id={$this->segment_id}";
			$fields[] = "number={$this->number}";
			$fields[] = $this->suffix ? "suffix='{$this->suffix}'" : "suffix=null";
			$fields[] = $this->addressType ? "addressType='{$this->addressType}'" : "addressType=null";
			$fields[] = "city_id={$this->city_id}";
			$fields[] = $this->zip ? "zip={$this->zip}" : "zip=null";
			$fields[] = $this->zipplus4 ? "zipplus4={$this->zipplus4}" : "zipplus4=null";
			$fields[] = $this->status_id ? "status_id={$this->status_id}" : "status_id=null";
			$fields[] = $this->active ? "active='{$this->active}'" : "active=null";
			$fields[] = $this->censusBlockFIPSCode ? "censusBlockFIPSCode='{$this->censusBlockFIPSCode}'" : "censusBlockFIPSCode=null";
			$fields[] = $this->statePlaneX ? "statePlaneX={$this->statePlaneX}" : "statePlaneX=null";
			$fields[] = $this->statePlaneY ? "statePlaneY={$this->statePlaneY}" : "statePlaneY=null";
			$fields[] = $this->latitude ? "latitude='{$this->latitude}'" : "latitude=null";
			$fields[] = $this->longitude ? "longitude='{$this->longitude}'" : "longitude=null";
			$fields[] = $this->startDate ? "startDate='{$this->startDate}'" : "startDate=null";
			$fields[] = $this->endDate ? "endDate='{$this->endDate}'" : "endDate=null";
			$fields[] = $this->notes ? "notes='{$this->notes}'" : "notes=null";

			$fields = implode(",",$fields);


			if ($this->id) { $this->update($fields); }
			else { $this->insert($fields); }
		}

		private function update($fields)
		{
			global $PDO;

			$sql = "update addresses set $fields where id={$this->id}";
			if (false === $PDO->exec($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
		}

		private function insert($fields)
		{
			global $PDO;

			$sql = "insert addresses set $fields";
			if (false === $PDO->exec($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
			$this->id = $PDO->lastInsertID();
		}


		public function getId() { return $this->id; }
		public function getPlace_id() { return $this->place_id; }
		public function getSegment_id() { return $this->segment_id; }
		public function getNumber() { return $this->number; }
		public function getSuffix() { return $this->suffix; }
		public function getAddressType() { return $this->addressType; }
		public function getCity_id() { return $this->city_id; }
		public function getZip() { return $this->zip; }
		public function getZipplus4() { return $this->zipplus4; }
		public function getStatus_id() { return $this->status_id; }
		public function getActive() { return $this->active; }
		public function getCensusBlockFIPSCode() { return $this->censusBlockFIPSCode; }
		public function getStatePlaneX() { return $this->statePlaneX; }
		public function getStatePlaneY() { return $this->statePlaneY; }
		public function getLatitude() { return $this->latitude; }
		public function getLongitude() { return $this->longitude; }
		public function getStartDate() { return $this->startDate; }
		public function getEndDate() { return $this->endDate; }
		public function getNotes() { return $this->notes; }

		public function getPlace()
		{
			if ($this->place_id)
			{
				if (!$this->place) { $this->place = new Place($this->place_id); }
				return $this->place;
			}
			else return null;
		}
		public function getSegment()
		{
			if ($this->segment_id)
			{
				if (!$this->segment) { $this->segment = new Segment($this->segment_id); }
				return $this->segment;
			}
			else return null;
		}
		public function getCity()
		{
			if ($this->city_id)
			{
				if (!$this->city) { $this->city = new City($this->city_id); }
				return $this->city;
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
		public function setSegment_id($int) { $this->segment = new Segment($int); $this->segment_id = $int; }
		public function setNumber($int) { $this->number = ereg_replace("[^0-9]","",$int); }
		public function setSuffix($string) { $this->suffix = sanitizeString($string); }
		public function setAddressType($string) { $this->addressType = sanitizeString($string); }
		public function setCity_id($int) { $this->city = new City($int); $this->city_id = $int; }
		public function setZip($int) { $this->zip = ereg_replace("[^0-9]","",$int); }
		public function setZipplus4($int) { $this->zipplus4 = ereg_replace("[^0-9]","",$int); }
		public function setStatus_id($int) { $this->status = new Status($int); $this->status_id = $int; }
		public function setActive($string) { $this->active = sanitizeString($string); }
		public function setCensusBlockFIPSCode($string) { $this->censusBlockFIPSCode = sanitizeString($string); }
		public function setStatePlaneX($int) { $this->statePlaneX = ereg_replace("[^0-9]","",$int); }
		public function setStatePlaneY($int) { $this->statePlaneY = ereg_replace("[^0-9]","",$int); }
		public function setLatitude($float) { $this->latitude = ereg_replace("[^0-9.]","",$float); }
		public function setLongitude($float) { $this->longitude = ereg_replace("[^0-9.]","",$float); }
		public function setStartDate($date) { $this->startDate = $date; }
		public function setEndDate($date) { $this->endDate = $date; }
		public function setNotes($string) { $this->notes = sanitizeString($string); }
	}
?>