<?php
/**
* @copyright Copyright (C) 2006 City of Bloomington, Indiana. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
*/
	class Place extends ActiveRecord
	{
		private $id;
		private $name;
		private $township_id;
		private $jurisdiction_id;
		private $trashPickupDay_id;
		private $recyclingPickupWeek_id;
		private $mailable;
		private $livable;
		private $section;
		private $quarterSection;
		private $class;
		private $placeType_id;
		private $censusBlockFIPSCode;
		private $statePlaneX;
		private $statePlaneY;
		private $latitude;
		private $longitude;
		private $startDate;
		private $endDate;
		private $placeStatus_id;
		private $plat_id;
		private $lotNumber;

		private $township;
		private $jurisdiction;
		private $trashPickupDay;
		private $recyclingPickupWeek;
		private $placeType;
		private $status;
		private $plat;

		private $addressList;
		private $buildings = array();
		private $deletedBuildingIds = array();
		private $newBuildingIds = array();


		public function __construct($id=null)
		{
			global $PDO;

			if ($id)
			{
				$sql = "select * from places where id=?";
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
				$now = getdate();
				$this->startDate = "$now[year]-$now[mon]-$now[mday]";
				$this->placeStatus_id = 1;
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
			if (!$this->jurisdiction_id || !$this->placeStatus_id || !$this->mailable || !$this->livable || !$this->placeType_id)
			{ throw new Exception("missingRequiredFields"); }


			$fields = array();
			$fields['name'] = $this->name ? $this->name : null;
			$fields['township_id'] = $this->township_id ? $this->township_id : null;
			$fields['jurisdiction_id'] = $this->jurisdiction_id ? $this->jurisdiction_id : null;
			$fields['trashPickupDay_id'] = $this->trashPickupDay_id ? $this->trashPickupDay_id : null;
			$fields['recyclingPickupWeek_id'] = $this->recyclingPickupWeek_id ? $this->recyclingPickupWeek_id : null;
			$fields['mailable'] = $this->mailable ? $this->mailable : null;
			$fields['livable'] = $this->livable ? $this->livable : null;
			$fields['section'] = $this->section ? $this->section : null;
			$fields['quarterSection'] = $this->quarterSection ? $this->quarterSection : null;
			$fields['class'] = $this->class ? $this->class : null;
			$fields['placeType_id'] = $this->placeType_id ? $this->placeType_id : null;
			$fields['censusBlockFIPSCode'] = $this->censusBlockFIPSCode ? $this->censusBlockFIPSCode : null;
			$fields['statePlaneX'] = $this->statePlaneX ? $this->statePlaneX : null;
			$fields['statePlaneY'] = $this->statePlaneY ? $this->statePlaneY : null;
			$fields['latitude'] = $this->latitude ? $this->latitude : null;
			$fields['longitude'] = $this->longitude ? $this->longitude : null;
			$fields['startDate'] = $this->startDate ? $this->startDate : null;
			$fields['endDate'] = $this->endDate ? $this->endDate : null;
			$fields['placeStatus_id'] = $this->placeStatus_id ? $this->placeStatus_id : null;
			$fields['plat_id'] = $this->plat_id ? $this->plat_id : null;
			$fields['lotNumber'] = $this->lotNumber ? $this->lotNumber : null;

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

			if (count($this->getBuildings())) { $this->saveBuildings(); }
		}

		private function update($values,$preparedFields)
		{
			global $PDO;

			$sql = "update places set $preparedFields where id={$this->id}";
			if (false === $query = $PDO->prepare($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
			if (false === $query->execute($values)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
		}

		private function insert($values,$preparedFields)
		{
			global $PDO;

			$sql = "insert places set $preparedFields";
			if (false === $query = $PDO->prepare($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
			if (false === $query->execute($values)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
			$this->id = $PDO->lastInsertID();

			$event = new PlaceHistory();
			$event->setPlace_id($this->id);
			$event->setAction("Created");
			$event->save();
		}


		public function addBuilding($building)
		{
			$this->getBuildings();
			$this->newBuildingIds[] = $building->getId();
			$this->buildings[] = $building;
		}
		public function deleteBuilding($building_id)
		{
			foreach($this->getBuildings() as $i=>$building)
			{
				if ($building->getId() == $building_id)
				{
					unset($this->buildings[$i]);
					$this->deletedBuildingIds[] = $building_id;
				}
			}
		}
		public function saveBuildings()
		{
			global $PDO;

			# Clean out all the deleted buildings
			if (count($this->deletedBuildingIds))
			{
				$ids = implode(",",$this->deletedBuildingIds);
				$sql = "delete from building_places where place_id={$this->id} and building_id in ($ids)";
				if (false === $PDO->exec($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
			}


			# Add in all the new buildings
			if (count($this->newBuildingIds))
			{
				$statement = $PDO->prepare("insert building_places values (?,{$this->id})");
				foreach($this->newBuildingIds as $building_id)
				{
					try { $statement->execute(array("$building_id")); }
					catch (PDOException $e) { throw new Exception ($e); }
				}
			}
		}




		public function getId() { return $this->id; }
		public function getName() { return $this->name; }
		public function getTownship_id() { return $this->township_id; }
		public function getJurisdiction_id() { return $this->jurisdiction_id; }
		public function getTrashPickupDay_id() { return $this->trashPickupDay_id; }
		public function getRecyclingPickupWeek_id() { return $this->recyclingPickupWeek_id; }
		public function getMailable() { return $this->mailable; }
		public function getLivable() { return $this->livable; }
		public function getSection() { return $this->section; }
		public function getQuarterSection() { return $this->quarterSection; }
		public function getClass() { return $this->class; }
		public function getPlaceType_id() { return $this->placeType_id; }
		public function getCensusBlockFIPSCode() { return $this->censusBlockFIPSCode; }
		public function getStatePlaneX() { return $this->statePlaneX; }
		public function getStatePlaneY() { return $this->statePlaneY; }
		public function getLatitude() { return $this->latitude; }
		public function getLongitude() { return $this->longitude; }
		public function getStartDate() { return $this->startDate; }
		public function getEndDate() { return $this->endDate; }
		public function getPlaceStatus_id() { return $this->placeStatus_id; }
		public function getStatus_id() { return $this->placeStatus_id; }
		public function getPlat_id() { return $this->plat_id; }
		public function getLotNumber() { return $this->lotNumber; }
		public function getTownship()
		{
			if ($this->township_id)
			{
				if (!$this->township) { $this->township = new Township($this->township_id); }
				return $this->township;
			}
			else return null;
		}
		public function getJurisdiction()
		{
			if ($this->jurisdiction_id)
			{
				if (!$this->jurisdiction) { $this->jurisdiction = new Jurisdiction($this->jurisdiction_id); }
				return $this->jurisdiction;
			}
			else return null;
		}
		public function getTrashPickupDay()
		{
			if ($this->trashPickupDay_id)
			{
				if (!$this->trashPickupDay) { $this->trashPickupDay = new TrashPickupDay($this->trashPickupDay_id); }
				return $this->trashPickupDay;
			}
			else return null;
		}
		public function getRecyclingPickupWeek()
		{
			if ($this->recyclingPickupWeek_id)
			{
				if (!$this->recyclingPickupWeek) { $this->recyclingPickupWeek = new RecyclingPickupWeek($this->recyclingPickupWeek_id); }
				return $this->recyclingPickupWeek;
			}
			else return null;
		}
		public function getAddressList()
		{
			if (!$this->addressList) { $this->addressList = new AddressList(array("place_id"=>$this->id),"startDate desc"); }
			return $this->addressList;
		}
		public function getAddresses() { return $this->getAddressList(); }
		public function getPlaceType()
		{
			if ($this->placeType_id)
			{
				if (!$this->placeType) { $this->placeType = new PlaceType($this->placeType_id); }
				return $this->placeType;
			}
			else return null;
		}
		public function getStatus()
		{
			if ($this->placeStatus_id)
			{
				if (!$this->status) { $this->status = new Status("place",$this->placeStatus_id); }
				return $this->status;
			}
			else return null;
		}
		public function getBuildings()
		{
			if (!count($this->buildings))
			{
				$list = new BuildingList(array("place_id"=>$this->id));
				foreach($list as $building) { $this->buildings[] = $building; }
			}
			return $this->buildings;
		}
		public function getHistory() { return new PlaceHistoryList(array("place_id"=>$this->id)); }
		public function getUnits() { return new UnitList(array("place_id"=>$this->id)); }
		public function getUnassignedUnits() { return new UnitList(array("place_id"=>$this->id,"building_id"=>"null")); }
		public function getPlat()
		{
			if ($this->plat_id)
			{
				if (!$this->plat) { $this->plat = new Plat($this->plat_id); }
				return $this->plat;
			}
			else return null;
		}

		public function deletePlat() { unset($this->plat); unset($this->plat_id); }


		public function setName($string) { $this->name = trim($string); }
		public function setTownship_id($int) { $this->township = new Township($int); $this->township_id = $int; }
		public function setJurisdiction_id($int) { $this->jurisdiction = new Jurisdiction($int); $this->jurisdiction_id = $int; }
		public function setTrashPickupDay_id($int) { $this->trashPickupDay = new TrashPickupDay($int); $this->trashPickupDay_id = $int; }
		public function setRecyclingPickupWeek_id($int) { $this->recyclingPickupWeek = new RecyclingPickupWeek($int); $this->recyclingPickupWeek_id = $int; }
		public function setMailable($int) { $this->mailable = ereg_replace("[^0-9]","",$int); }
		public function setLivable($int) { $this->livable = ereg_replace("[^0-9]","",$int); }
		public function setSection($string) { $this->section = trim($string); }
		public function setQuarterSection($string) { $this->quarterSection = ereg_replace("[^A-Z]","",strtoupper($string)); }
		public function setClass($string) { $this->class = trim($string); }
		public function setPlaceType_id($int) { $this->placeType = new PlaceType($int); $this->placeType_id = $int; }
		public function setCensusBlockFIPSCode($string) { $this->censusBlockFIPSCode = trim($string); }
		public function setStatePlaneX($int) { $this->statePlaneX = ereg_replace("[^0-9]","",$int); }
		public function setStatePlaneY($int) { $this->statePlaneY = ereg_replace("[^0-9]","",$int); }
		public function setLatitude($float) { $this->latitude = ereg_replace("[^0-9.]","",$float); }
		public function setLongitude($float) { $this->longitude = ereg_replace("[^0-9.]","",$float); }
		public function setStartDate($date) { $this->startDate = is_array($date) ? $this->dateArrayToString($date) : $date; }
		public function setEndDate($date) { $this->endDate = is_array($date) ? $this->dateArrayToString($date) : $date; }
		public function setPlaceStatus_id($int) { $this->status = new Status("place",$int); $this->placeStatus_id = $int; }
		public function setStatus_id($int) { $this->setPlaceStatus_id($int); }
		public function setStatus($status) { $this->placeStatus_id = $status->getId(); $this->status = $status; }
		public function setPlat_id($int) { $this->plat = new Plat($int); $this->plat_id = $int; }
		public function setLotNumber($int) { $this->lotNumber = ereg_replace("[^0-9]","",$int); }
		public function setPlat($plat) { $this->plat_id = $plat->getId(); $this->plat = $plat; }
	}
?>