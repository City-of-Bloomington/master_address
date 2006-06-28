<?php
	class District extends ActiveRecord
	{
		private $id;
		private $name;
		private $districtType_id;

		private $districtType;

		private $places = array();
		private $newPlaceIDs = array();


		public function __construct(PDO $PDO,$id=null)
		{
			$this->PDO = $PDO;

			if ($id)
			{
				$sql = "select * from districts where id=$id";
				$result = $this->PDO->query($sql);
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

			if (count($this->getPlaces())) { $this->savePlaces(); }
		}

		private function update($fields)
		{
			$sql = "update districts set $fields where id={$this->id}";
			if (false === $this->PDO->exec($sql)) { $e = $this->PDO->errorInfo(); throw new Exception($sql.$e[2]); }
		}

		private function insert($fields)
		{
			$sql = "insert districts set $fields";
			if (false === $this->PDO->exec($sql)) { $e = $this->PDO->errorInfo(); throw new Exception($sql.$e[2]); }
			$this->id = $this->PDO->lastInsertID();
		}

		public function addPlace($place)
		{
			$this->getPlaces();
			$this->newPlaceIDs[] = $place->getId();
			$this->places[] = $place;
		}

		public function deletePlace($place_id)
		{
			foreach($this->places as $i=>$place) { if($place->getId() == $place_id) unset($this->places[$i]); }
		}

		private function savePlaces()
		{
			$ids = array();
			foreach($this->getPlaces() as $place) { $ids[] = $place->getId(); }
			$ids = implode(",",$ids);

			$sql = "delete from district_places where district_id={$this->id} and place_id not in ($ids)";
			if (false === $this->PDO->exec($sql)) { $e = $this->PDO->errorInfo(); throw new Exception($sql.$e[2]); }

			foreach ($this->newPlaceIDs as $place_id)
			{
				$sql = "insert district_places values($place_id,{$this->id})";
				if (false === $this->PDO->exec($sql)) { $e = $this->PDO->errorInfo(); throw new Exception($sql.$e[2]); }
			}
		}



		public function getId() { return $this->id; }
		public function getName() { return $this->name; }
		public function getDistrictType_id() { return $this->districtType_id; }
		public function getDistrictType()
		{
			if (!$this->districtType) { $this->districtType = new DistrictType($this->PDO,$this->districtType_id); }
			return $this->districtType;
		}
		public function getPlaces()
		{
			if (!count($this->places))
			{
				$placeList = new PlaceList($this->PDO);
				$placeList->find(array("district_id"=>$this->id));
				foreach($placeList as $place) { $this->places[] = $place; }
			}
			return $this->places;
		}

		public function setName($string) { $this->name = sanitizeString($string); }
		public function setDistrictType_id($districtType_id)
		{
			$this->districtType = new DistrictType($this->PDO,$districtType_id);
			$this->districtType_id = $districtType_id;
		}

	}
?>