<?php
/**
* @copyright Copyright (C) 2006 City of Bloomington, Indiana. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
*/
	class District extends ActiveRecord
	{
		private $id;
		private $name;
		private $districtType_id;

		private $districtType;

		private $places = array();
		private $deletedPlaceIDs = array();
		private $newPlaceIDs = array();


		public function __construct($id=null)
		{
			global $PDO;

			if ($id)
			{
				$sql = "select * from districts where id=?";
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


		/**
		 * This generates generic SQL that should work right away.
		 * You can replace this $fields code with your own custom SQL
		 * for each property of this class,
		 */
		public function save()
		{
			# Check for required fields here.  Throw an exception if anything is missing.
			if (!$this->name || !$this->districtType_id) { throw new Exception("missingRequiredFields"); }


			$fields = array();
			$fields['name'] = $this->name ? $this->name : null;
			$fields['districtType_id'] = $this->districtType_id ? $this->districtType_id : null;

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

			if (count($this->deletedPlaceIDs) || count($this->newPlaceIDs)) { $this->savePlaces(); }
		}

		private function update($values,$preparedFields)
		{
			global $PDO;

			$sql = "update districts set $preparedFields where id={$this->id}";
			if (false === $query = $PDO->prepare($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
			if (false === $query->execute($values)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
		}

		private function insert($values,$preparedFields)
		{
			global $PDO;

			$sql = "insert districts set $preparedFields";
			if (false === $query = $PDO->prepare($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
			if (false === $query->execute($values)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
			$this->id = $PDO->lastInsertID();
		}

		public function addPlace($place)
		{
			$this->getPlaces();
			$this->newPlaceIDs[] = $place->getId();
			$this->places[] = $place;
		}

		public function deletePlace($place_id)
		{
			foreach($this->places as $i=>$place)
			{
				if($place->getId() == $place_id)
				{
					unset($this->places[$i]);
					$this->deletedPlaceIDs[] = $place_id;
				}
			}
		}

		private function savePlaces()
		{
			global $PDO;

			# Clean out all the deleted ones
			if (count($this->deletedPlaceIDs))
			{
				$ids = implode(",",$this->deletedPlaceIDs);
				$sql = "delete from district_places where district_id={$this->id} and place_id in ($ids)";
				if (false === $PDO->exec($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
			}

			# Add in the new ones
			foreach ($this->newPlaceIDs as $place_id)
			{
				$sql = "insert district_places values($place_id,{$this->id})";
				if (false === $PDO->exec($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
			}
		}



		public function getId() { return $this->id; }
		public function getName() { return $this->name; }
		public function getDistrictType_id() { return $this->districtType_id; }
		public function getDistrictType()
		{
			if (!$this->districtType) { $this->districtType = new DistrictType($this->districtType_id); }
			return $this->districtType;
		}
		public function getPlaces()
		{
			if (!count($this->places))
			{
				$placeList = new PlaceList();
				$placeList->find(array("district_id"=>$this->id));
				foreach($placeList as $place) { $this->places[] = $place; }
			}
			return $this->places;
		}

		public function setName($string) { $this->name = trim($string); }
		public function setDistrictType_id($districtType_id)
		{
			$this->districtType = new DistrictType($districtType_id);
			$this->districtType_id = $districtType_id;
		}

	}
?>