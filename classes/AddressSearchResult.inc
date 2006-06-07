<?php
	class AddressSearchResult
	{
		private $fullAddress;
		private $address_id;
		private $segment_id;
		private $street_id;
		private $name_id;
		private $place_id;
		private $unit_id;

		private $address;
		private $segment;
		private $street;
		private $name;
		private $place;
		private $unit;



		public function __construct($fullAddress,$address_id,$segment_id,$street_id,$name_id,$place_id,$unit_id)
		{
			$this->fullAddress = $fullAddress;
			$this->address_id = $address_id;
			$this->segment_id = $segment_id;
			$this->street_id = $street_id;
			$this->name_id = $name_id;
			$this->place_id = $place_id;
			$this->unit_id = $unit_id;
		}

		public function getFullAddress() { return $this->fullAddress; }
		public function getAddress_id() { return $this->address_id; }
		public function getSegment_id() { return $this->segment_id; }
		public function getStreet_id() { return $this->street_id; }
		public function getName_id() { return $this->name_id; }
		public function getPlace_id() { return $this->place_id; }
		public function getUnit_id() { return $this->unit_id; }

		public function getAddress() { if (!$this->address) { $this->address = new Address($this->address_id); } return $this->address; }
		public function getSegment() { if (!$this->segment) { $this->segment = new Segment($this->segment_id); } return $this->segment; }
		public function getStreet() { if (!$this->street) { $this->street = new Street($this->street_id); } return $this->street; }
		public function getName() { if (!$this->name) { $this->name = new Name($this->name_id); } return $this->name; }
		public function getPlace() { if (!$this->place) { $this->place = new Place($this->place_id); } return $this->place; }
		public function getUnit()
		{
			if ($this->unit_id)
			{
				if (!$this->unit) { $this->unit = new Unit($this->unit_id); }
				return $this->unit;
			}
			else return null;
		}
	}
?>