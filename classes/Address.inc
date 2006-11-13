<?php
/**
 * @copyright Copyright (C) 2006 City of Bloomington, Indiana. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 */
	class Address extends ActiveRecord
	{
		private $id;
		private $place_id;
		private $street_id;
		private $segment_id;
		private $number;
		private $suffix;
		private $addressType;
		private $city_id;
		private $zip;
		private $zipplus4;
		private $addressStatus_id;
		private $active;
		private $startDate;
		private $endDate;
		private $notes;

		private $place;
		private $segment;
		private $city;
		private $status;

		private $street;
		private $streetName;
		private $fullAddress;


		public function __construct($id=null)
		{
			global $PDO;

			if ($id)
			{
				$sql = "select * from addresses where id=?";
				try
				{
					$query = $PDO->prepare($sql);
					$query->execute(array($id));
				}
				catch (Exception $e) { throw $e; }

				$result = $query->fetchAll();
				foreach($result[0] as $field=>$value) { if ($value) $this->$field = $value; }
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
			if (!$this->place_id || !$this->street_id || !$this->segment_id || !$this->number || !$this->city_id) { throw new Exception("missingRequiredFields"); }


			$fields = array();
			$fields['place_id'] = $this->place_id ? $this->place_id : null;
			$fields['street_id'] = $this->street_id ? $this->street_id : null;
			$fields['segment_id'] = $this->segment_id ? $this->segment_id : null;
			$fields['number'] = $this->number ? $this->number : null;
			$fields['suffix'] = $this->suffix ? $this->suffix : null;
			$fields['addressType'] = $this->addressType ? $this->addressType : null;
			$fields['city_id'] = $this->city_id ? $this->city_id : null;
			$fields['zip'] = $this->zip ? $this->zip : null;
			$fields['zipplus4'] = $this->zipplus4 ? $this->zipplus4 : null;
			$fields['addressStatus_id'] = $this->addressStatus_id ? $this->addressStatus_id : null;
			$fields['active'] = $this->active ? $this->active : null;
			$fields['startDate'] = $this->startDate ? $this->startDate : null;
			$fields['endDate'] = $this->endDate ? $this->endDate : null;
			$fields['notes'] = $this->notes ? $this->notes : null;

			# Split the fields up into a preparedFields array and a values array.
			# PDO->execute cannot take an associative array for values, so we have
			# to strip out the keys from $fields
			$preparedFields = array();
			foreach($fields as $key=>$value)
			{
				$preparedFields[] = "$key=?";
				$values[] = $value;
			}
			$preparedFields = implode(",",$preparedFields);


			if ($this->id) { $this->update($values,$preparedFields); }
			else { $this->insert($values,$preparedFields); }
		}

		private function update($values,$preparedFields)
		{
			global $PDO;

			$sql = "update addresses set $preparedFields where id={$this->id}";
			if (false === $query = $PDO->prepare($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
			if (false === $query->execute($values)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
		}

		private function insert($values,$preparedFields)
		{
			global $PDO;

			$sql = "insert addresses set $preparedFields";
			if (false === $query = $PDO->prepare($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
			if (false === $query->execute($values)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
			$this->id = $PDO->lastInsertID();
		}

		public function __toString() { return $this->getFullAddress(); }

		public function getId() { return $this->id; }
		public function getPlace_id() { return $this->place_id; }
		public function getStreet_id() { return $this->street_id; }
		public function getSegment_id() { return $this->segment_id; }
		public function getNumber() { return $this->number; }
		public function getSuffix() { return $this->suffix; }
		public function getAddressType() { return $this->addressType; }
		public function getCity_id() { return $this->city_id; }
		public function getZip() { return $this->zip; }
		public function getZipplus4() { return $this->zipplus4; }
		public function getAddressStatus_id() { return $this->addressStatus_id; }
		public function getStatus_id() { return $this->addressStatus_id; }
		public function getActive() { return $this->active; }
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
			if ($this->addressStatus_id)
			{
				if (!$this->status) { $this->status = new Status("address",$this->addressStatus_id); }
				return $this->status;
			}
			else return null;
		}
		public function getStreet()
		{
			if ($this->street_id)
			{
				if (!$this->street) { $this->street = new Street($this->street_id); }
				return $this->street;
			}
			else return null;
		}
		public function getStreetName()
		{
			return $this->getStreet()->getStreetName();
		}
		public function getFullAddress()
		{
			if (!$this->fullAddress)
			{
				$temp = array();
				$temp[] = $this->number;
				if ($this->suffix) { $temp[] = $this->suffix; }
				$temp[] = $this->getStreet()->getFullStreetName();
				$this->fullAddress = implode(" ",$temp);
			}
			return $this->fullAddress;
		}



		public function setPlace_id($int) { $this->place = new Place($int); $this->place_id = $int; }
		public function setStreet_id($int) { $this->street = new Street($int); $this->street_id = $int; }
		public function setSegment_id($int) { $this->segment = new Segment($int); $this->segment_id = $int; }
		public function setNumber($int) { $this->number = ereg_replace("[^0-9]","",$int); }
		public function setSuffix($string) { $this->suffix = trim($string); }
		public function setAddressType($string) { $this->addressType = trim($string); }
		public function setCity_id($int) { $this->city = new City($int); $this->city_id = $int; }
		public function setZip($int) { $this->zip = ereg_replace("[^0-9]","",$int); }
		public function setZipplus4($int) { $this->zipplus4 = ereg_replace("[^0-9]","",$int); }
		public function setAddressStatus_id($int) { $this->status = new Status("address",$int); $this->addressStatus_id = $int; }
		public function setStatus_id($int) { $this->setAddressStatus_id($int); }
		public function setActive($string) { $this->active = trim($string); }
		public function setStartDate($date) { $this->startDate = is_array($date) ? $this->dateArrayToString($date) : $date; }
		public function setEndDate($date) { $this->endDate = is_array($date) ? $this->dateArrayToString($date) : $date; }
		public function setNotes($string) { $this->notes = trim($string); }

		public function setPlace($place) { $this->place_id = $place->getId(); $this->place = $place; }
		public function setStreet($street) { $this->street_id = $street->getId(); $this->street = $street; }
		public function setSegment($segment) { $this->segment_id = $segment->getId(); $this->segment = $segment; }
	}
?>