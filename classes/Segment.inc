<?php
	require_once(APPLICATION_HOME."/classes/Jurisdiction.inc");
	require_once(APPLICATION_HOME."/classes/Status.inc");
	require_once(APPLICATION_HOME."/classes/Direction.inc");
	require_once(APPLICATION_HOME."/classes/StreetList.inc");
	class Segment
	{
		private $id;
		private $tag;
		private $startingNumber;
		private $endingNumber;
		private $jurisdiction_id;
		private $status_id;
		private $notes;
		private $speedLimit;
		private $INDOTSegmentID;
		private $transportationClass;
		private $maintenanceResponsibility;
		private $leftLowNumber;
		private $leftHighNumber;
		private $rightLowNumber;
		private $rightHighNumber;
		private $rcinode1;
		private $rcinode2;
		private $lowNode;
		private $highNode;
		private $lowXCoordinate;
		private $lowYCoordinate;
		private $highXCoordinate;
		private $highYCoordinate;
		private $travel;
		private $travelDirection_id;
		private $complete;
		private $rclBack;
		private $rclNext;
		private $classRow;
		private $mapArea;
		private $lastUpdatedDate;
		private $lastUpdatedAction;
		private $lastUpdatedUser_id;


		private $jurisdiction;
		private $status;
		private $travelDirection;
		private $lastUpdatedUser;

		private $streetList;


		public function __construct($id=null)
		{
			global $PDO;

			if ($id)
			{
				$sql = "select * from segments where id=$id";
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


			# This generates generic SQL that should work right away.
			# You can (and maybe should) replace this $fields code with your own custom SQL
			# for each property of this class,
			$fields = array();
			foreach($this as $field=>$value)
			{
				if (!is_numeric($field))
				{
					if ($value) { $fields[] = "$field='$value'"; } else { $fields[] = "$field=null"; }
				}
			}
			$fields = implode(",",$fields);


			if ($this->id) { $this->update($fields); }
			else { $this->insert($fields); }
		}

		private function update($fields)
		{
			global $PDO;

			$sql = "update segments set $fields where id={$this->id}";
			if (false === $PDO->exec($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
		}

		private function insert($fields)
		{
			global $PDO;

			$sql = "insert segments set $fields";
			if (false === $PDO->exec($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
			$this->id = $PDO->lastInsertID();
		}


		public function getId() { return $this->id; }
		public function getTag() { return $this->tag; }
		public function getStartingNumber() { return $this->startingNumber; }
		public function getEndingNumber() { return $this->endingNumber; }
		public function getJurisdiction_id() { return $this->jurisdiction_id; }
		public function getStatus_id() { return $this->status_id; }
		public function getNotes() { return $this->notes; }
		public function getSpeedLimit() { return $this->speedLimit; }
		public function getINDOTSegmentID() { return $this->INDOTSegmentID; }
		public function getTransportationClass() { return $this->transportationClass; }
		public function getMaintenanceResponsibility() { return $this->maintenanceResponsibility; }
		public function getLeftLowNumber() { return $this->leftLowNumber; }
		public function getLeftHighNumber() { return $this->leftHighNumber; }
		public function getRightLowNumber() { return $this->rightLowNumber; }
		public function getRightHighNumber() { return $this->rightHighNumber; }
		public function getRcinode1() { return $this->rcinode1; }
		public function getRcinode2() { return $this->rcinode2; }
		public function getLowNode() { return $this->lowNode; }
		public function getHighNode() { return $this->highNode; }
		public function getLowXCoordinate() { return $this->lowXCoordinate; }
		public function getLowYCoordinate() { return $this->lowYCoordinate; }
		public function getHighXCoordinate() { return $this->highXCoordinate; }
		public function getHighYCoordinate() { return $this->highYCoordinate; }
		public function getTravel() { return $this->travel; }
		public function getTravelDirection_id() { return $this->travelDirection_id; }
		public function getComplete() { return $this->complete; }
		public function getRclBack() { return $this->rclBack; }
		public function getRclNext() { return $this->rclNext; }
		public function getClassRow() { return $this->classRow; }
		public function getMapArea() { return $this->mapArea; }
		public function getLastUpdatedDate() { return $this->lastUpdatedDate; }
		public function getLastUpdatedAction() { return $this->lastUpdatedAction; }
		public function getLastUpdatedUser_id() { return $this->lastUpdatedUser_id; }

		public function getJurisdiction()
		{
			if ($this->jurisdiction_id)
			{
				if (!$this->jurisdiction) { $this->jurisdiction = new Jurisdiction($this->jurisdiction_id); }
				return $this->jurisdiction;
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
		public function getTravelDirection()
		{
			if ($this->travelDirection_id)
			{
				if (!$this->travelDirection) { $this->travelDirection = new Direction($this->travelDirection_id); }
				return $this->travelDirection;
			}
			else return null;
		}
		public function getLastUpdatedUser()
		{
			if ($this->lastUpdatedUser_id)
			{
				if (!$this->lastUpdatedUser) { $this->lastUpdatedUser = new User($this->lastUpdatedUser_id); }
				return $this->lastUpdatedUser;
			}
			else return null;
		}
		public function getStreetList()
		{
			if (!$this->streetList)
			{
				$this->streetList = new StreetList();
				$this->streetList->find(array("segment_id"=>$this->id));
			}
			return $this->streetList;
		}




		public function setTag($string) { $this->tag = sanitizeString($string); }
		public function setStartingNumber($int) { $this->startingNumber = ereg_replace("[^0-9]","",$int); }
		public function setEndingNumber($int) { $this->endingNumber = ereg_replace("[^0-9]","",$int); }
		public function setJurisdiction_id($int) { $this->jurisdiction = new Jurisdiction($int); $this->jurisdiction_id = $int; }
		public function setStatus_id($int) { $this->status = new Status($int); $this->status_id = $int; }
		public function setNotes($string) { $this->notes = sanitizeString($string); }
		public function setSpeedLimit($int) { $this->speedLimit = ereg_replace("[^0-9]","",$int); }
		public function setINDOTSegmentID($string) { $this->INDOTSegmentID = sanitizeString($string); }
		public function setTransportationClass($string) { $this->transportationClass = ereg_replace("[^A-Z]","",strtoupper($string)); }
		public function setMaintenanceResponsibility($string) { $this->maintenanceResponsibility = sanitizeString($string); }
		public function setLeftLowNumber($int) { $this->leftLowNumber = ereg_replace("[^0-9]","",$int); }
		public function setLeftHighNumber($int) { $this->leftHighNumber = ereg_replace("[^0-9]","",$int); }
		public function setRightLowNumber($int) { $this->rightLowNumber = ereg_replace("[^0-9]","",$int); }
		public function setRightHighNumber($int) { $this->rightHighNumber = ereg_replace("[^0-9]","",$int); }
		public function setRcinode1($char) { $this->rcinode1 = ereg_replace("[^0-9a-z]","",strtolower($char)); }
		public function setRcinode2($char) { $this->rcinode2 = ereg_replace("[^0-9a-z]","",strtolower($char)); }
		public function setLowNode($char) { $this->lowNode = ereg_replace("[^0-9a-z]","",strtolower($char)); }
		public function setHighNode($char) { $this->highNode = ereg_replace("[^0-9a-z]","",strtolower($char)); }
		public function setLowXCoordinate($int) { $this->lowXCoordinate = ereg_replace("[^0-9]","",$int); }
		public function setLowYCoordinate($int) { $this->lowYCoordinate = ereg_replace("[^0-9]","",$int); }
		public function setHighXCoordinate($int) { $this->highXCoordinate = ereg_replace("[^0-9]","",$int); }
		public function setHighYCoordinate($int) { $this->highYCoordinate = ereg_replace("[^0-9]","",$int); }
		public function setTravel($int) { $this->travel = ereg_replace("[^0-9]","",$int); }
		public function setTravelDirection_id($int) { $this->travelDirection = new Direction($int); $this->travelDirection_id = $int; }
		public function setComplete($char) { $this->complete = $char; }
		public function setRclBack($char) { $this->rclBack = ereg_replace("[^0-9a-z]","",strtolower($char)); }
		public function setRclNext($char) { $this->rclNext = ereg_replace("[^0-9a-z]","",strtolower($char)); }
		public function setClassRow($int) { $this->classRow = ereg_replace("[^0-9]","",$int); }
		public function setMapArea($char) { $this->mapArea = $char; }
		public function setLastUpdatedDate($date) { $this->lastUpdatedDate = $date; }
		public function setLastUpdatedAction($string) { $this->lastUpdatedAction = sanitizeString($string); }
		public function setLastUpdatedUser_id($int) { $this->lastUpdatedUser = new User($int); $this->lastUpdatedBy = $int; }
	}
?>