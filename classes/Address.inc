<?php
	class Address
	{
		private $addressID;
		private $placeID;
		private $segmentID;
		private $number;
		private $suffix;
		private $addressType;
		private $city;
		private $zip;
		private $zipplus4;
		private $statusCode;
		private $active;
		private $censusBlockFIPSCode;
		private $statePlaneX;
		private $statePlaneY;
		private $latitude;
		private $longitude;
		private $startDate;
		private $endDate;
		private $notes;



		public function __construct($addressID=null)
		{
			if ($addressID)
			{
				$sql = "select * from addresses where addressID=$addressID";
				$result = mysql_query($sql) or throw new Exception($sql.mysql_error());
				if (mysql_num_rows($result))
				{
					$row = mysql_fetch_array($result);

					foreach($row as $field=>$value) { if ($value) $this->$field = $value; }
				}
				else { throw new Exception("uknownSomething"); }
			}
			else
			{
				# Set any default values for properties that need it here
			}
		}


		public function save()
		{
			$fields = array();
			foreach($this as $property=>$value)
			{
				list($field,$type) = explode(":",$property);
				if ($value) { $fields[] = "$field='$value'"; } else { $fields[] = "$field=null"; }
			}
			$fields = implode(",",$fields);


			if ($this->addressID) { $this->update($fields); }
			else { $this->insert($fields); }
		}

		private function update($fields)
		{
			$sql = "update addresses set $fields where addressID=$addressID";
			mysql_query($sql) or throw new Exception($sql.mysql_error());
		}

		private function insert(Array)
		{
			$sql = "insert addresses set $fields";
			mysql_query($sql) or throw new Exception($sql.mysql_error());
			$this->addressID = mysql_insert_id();
		}


		public function getAddressID() { return $this->addressID; }
		public function getPlaceID() { return $this->placeID; }
		public function getSegmentID() { return $this->segmentID; }
		public function getNumber() { return $this->number; }
		public function getSuffix() { return $this->suffix; }
		public function getAddressType() { return $this->addressType; }
		public function getCity() { return $this->city; }
		public function getZip() { return $this->zip; }
		public function getZipplus4() { return $this->zipplus4; }
		public function getStatusCode() { return $this->statusCode; }
		public function getActive() { return $this->active; }
		public function getCensusBlockFIPSCode() { return $this->censusBlockFIPSCode; }
		public function getStatePlaneX() { return $this->statePlaneX; }
		public function getStatePlaneY() { return $this->statePlaneY; }
		public function getLatitude() { return $this->latitude; }
		public function getLongitude() { return $this->longitude; }
		public function getStartDate() { return $this->startDate; }
		public function getEndDate() { return $this->endDate; }
		public function getNotes() { return $this->notes; }


		public function setAddressID($int) { $this->addressID = $int; }
		public function setPlaceID($int) { $this->placeID = $int; }
		public function setSegmentID($int) { $this->segmentID = $int; }
		public function setNumber($int) { $this->number = $int; }
		public function setSuffix($string) { $this->suffix = $string; }
		public function setAddressType($string) { $this->addressType = $string; }
		public function setCity($string) { $this->city = $string; }
		public function setZip($int) { $this->zip = $int; }
		public function setZipplus4($int) { $this->zipplus4 = $int; }
		public function setStatusCode($int) { $this->statusCode = $int; }
		public function setActive($string) { $this->active = $string; }
		public function setCensusBlockFIPSCode($string) { $this->censusBlockFIPSCode = $string; }
		public function setStatePlaneX($int) { $this->statePlaneX = $int; }
		public function setStatePlaneY($int) { $this->statePlaneY = $int; }
		public function setLatitude($float) { $this->latitude = $float; }
		public function setLongitude($float) { $this->longitude = $float; }
		public function setStartDate($date) { $this->startDate = $date; }
		public function setEndDate($date) { $this->endDate = $date; }
		public function setNotes($string) { $this->notes = $string; }

	}
?>