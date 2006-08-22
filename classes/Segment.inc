<?php
/**
* @copyright Copyright (C) 2006 City of Bloomington, Indiana. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
* This file is part of the City of Bloomington's web application Framework.
* This Framework is free software; you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation; either version 2 of the License, or
* (at your option) any later version.
*
* This Framework is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with Foobar; if not, write to the Free Software
* Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA
*/
	class Segment extends ActiveRecord
	{
		private $id;
		private $tag;
		private $status_id;
		private $dataOwner_id;
		private $description;
		private $station;
		private $lowAddressNumber;
		private $highAddressNumber;
		private $leftLowAddressNumber;
		private $leftHighAddressNumber;
		private $rightLowAddressNumber;
		private $rightHighAddressNumber;
		private $jurisdiction_id;
		private $jurisdictionLeft_id;
		private $jurisdictionRight_id;
		private $constructionStatus_id;
		private $dedicatedRightOfWay;
		private $maintainedBy;
		private $inventoryStatus_id;
		private $travelWayCode_id;
		private $travelDirection_id;
		private $speedLimit;
		private $speedLimitOrdinance;
		private $thoroughfareClass_id;
		private $segmentBack_id;
		private $segmentAhead_id;
		private $intersectionBack_id;
		private $intersectionAhead_id;
		private $lowAddressIntersection_id;
		private $highAddressIntersection_id;
		private $leftSidewalkStatus_id;
		private $rightSidewalkStatus_id;
		private $mapArea;
		private $mapStatus_id;
		private $mapSource;
		private $labelX;
		private $labelY;
		private $notes;


		private $status;
		private $dataOwner;
		private $jurisdiction;
		private $jurisdictionLeft;
		private $jurisdictionRight;
		private $constructionStatus;
		private $inventoryStatus;
		private $travelWayCode;
		private $travelDirection;
		private $thoroughfareClass;
		private $segmentBack;
		private $segmentAhead;
		private $intersectionBack;
		private $intersectionAhead;
		private $lowAddressIntersection;
		private $highAddressIntersection;
		private $leftSidewalkStatus;
		private $rightSidewalkStatus;
		private $mapStatus;


		private $addressList;
		private $placeList;
		private $streets;

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
			if (!$this->lowAddressNumber || !$this->highAddressNumber || !$this->jurisdiction_id || !$this->status_id || !$this->maintainedBy
			||!$this->leftLowAddressNumber || !$this->leftHighAddressNumber || !$this->rightLowAddressNumber || !$this->rightHighAddressNumber)
			{ throw new Exception("missingRequiredFields"); }


			# This generates generic SQL that should work right away.
			# You can (and maybe should) replace this $fields code with your own custom SQL
			# for each property of this class,
			$fields = array();
			$fields[] = $this->tag ? "tag='{$this->tag}'" : "tag=null";
			$fields[] = $this->status_id ? "status_id='{$this->status_id}'" : "status_id=null";
			$fields[] = $this->dataOwner_id ? "dataOwner_id='{$this->dataOwner_id}'" : "dataOwner_id=null";
			$fields[] = $this->description ? "description='{$this->description}'" : "description=null";
			$fields[] = $this->station ? "station='{$this->station}'" : "station=null";
			$fields[] = $this->lowAddressNumber ? "lowAddressNumber='{$this->lowAddressNumber}'" : "lowAddressNumber=null";
			$fields[] = $this->highAddressNumber ? "highAddressNumber='{$this->highAddressNumber}'" : "highAddressNumber=null";
			$fields[] = $this->leftLowAddressNumber ? "leftLowAddressNumber='{$this->leftLowAddressNumber}'" : "leftLowAddressNumber=null";
			$fields[] = $this->leftHighAddressNumber ? "leftHighAddressNumber='{$this->leftHighAddressNumber}'" : "leftHighAddressNumber=null";
			$fields[] = $this->rightLowAddressNumber ? "rightLowAddressNumber='{$this->rightLowAddressNumber}'" : "rightLowAddressNumber=null";
			$fields[] = $this->rightHighAddressNumber ? "rightHighAddressNumber='{$this->rightHighAddressNumber}'" : "rightHighAddressNumber=null";
			$fields[] = $this->jurisdiction_id ? "jurisdiction_id='{$this->jurisdiction_id}'" : "jurisdiction_id=null";
			$fields[] = $this->jurisdictionLeft_id ? "jurisdictionLeft_id='{$this->jurisdictionLeft_id}'" : "jurisdictionLeft_id=null";
			$fields[] = $this->jurisdictionRight_id ? "jurisdictionRight_id='{$this->jurisdictionRight_id}'" : "jurisdictionRight_id=null";
			$fields[] = $this->constructionStatus_id ? "constructionStatus_id='{$this->constructionStatus_id}'" : "constructionStatus_id=null";
			$fields[] = $this->dedicatedRightOfWay ? "dedicatedRightOfWay='{$this->dedicatedRightOfWay}'" : "dedicatedRightOfWay=null";
			$fields[] = $this->maintainedBy ? "maintainedBy='{$this->maintainedBy}'" : "maintainedBy=null";
			$fields[] = $this->inventoryStatus_id ? "inventoryStatus_id='{$this->inventoryStatus_id}'" : "inventoryStatus_id=null";
			$fields[] = $this->travelWayCode_id ? "travelWayCode_id='{$this->travelWayCode_id}'" : "travelWayCode_id=null";
			$fields[] = $this->travelDirection_id ? "travelDirection_id='{$this->travelDirection_id}'" : "travelDirection_id=null";
			$fields[] = $this->speedLimit ? "speedLimit='{$this->speedLimit}'" : "speedLimit=null";
			$fields[] = $this->speedLimitOrdinance ? "speedLimitOrdinance='{$this->speedLimitOrdinance}'" : "speedLimitOrdinance=null";
			$fields[] = $this->thoroughfareClass_id ? "thoroughfareClass_id='{$this->thoroughfareClass_id}'" : "thoroughfareClass_id=null";
			$fields[] = $this->segmentBack_id ? "segmentBack_id='{$this->segmentBack_id}'" : "segmentBack_id=null";
			$fields[] = $this->segmentAhead_id ? "segmentAhead_id='{$this->segmentAhead_id}'" : "segmentAhead_id=null";
			$fields[] = $this->intersectionBack_id ? "intersectionBack_id='{$this->intersectionBack_id}'" : "intersectionBack_id=null";
			$fields[] = $this->intersectionAhead_id ? "intersectionAhead_id='{$this->intersectionAhead_id}'" : "intersectionAhead_id=null";
			$fields[] = $this->lowAddressIntersection_id ? "lowAddressIntersection_id='{$this->lowAddressIntersection_id}'" : "lowAddressIntersection_id=null";
			$fields[] = $this->highAddressIntersection_id ? "highAddressIntersection_id='{$this->highAddressIntersection_id}'" : "highAddressIntersection_id=null";
			$fields[] = $this->leftSidewalkStatus_id ? "leftSidewalkStatus_id='{$this->leftSidewalkStatus_id}'" : "leftSidewalkStatus_id=null";
			$fields[] = $this->rightSidewalkStatus_id ? "rightSidewalkStatus_id='{$this->rightSidewalkStatus_id}'" : "rightSidewalkStatus_id=null";
			$fields[] = $this->mapArea ? "mapArea='{$this->mapArea}'" : "mapArea=null";
			$fields[] = $this->mapStatus_id ? "mapStatus_id='{$this->mapStatus_id}'" : "mapStatus_id=null";
			$fields[] = $this->mapSource ? "mapSource='{$this->mapSource}'" : "mapSource=null";
			$fields[] = $this->labelX ? "labelX='{$this->labelX}'" : "labelX=null";
			$fields[] = $this->labelY ? "labelY='{$this->labelY}'" : "labelY=null";
			$fields[] = $this->notes ? "notes='{$this->notes}'" : "notes=null";

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
		public function getStatus_id() { return $this->status_id; }
		public function getDataOwner_id() { return $this->dataOwner_id; }
		public function getDescription() { return $this->description; }
		public function getStation() { return $this->station; }
		public function getLowAddressNumber() { return $this->lowAddressNumber; }
		public function getHighAddressNumber() { return $this->highAddressNumber; }
		public function getLeftLowAddressNumber() { return $this->leftLowAddressNumber; }
		public function getLeftHighAddressNumber() { return $this->leftHighAddressNumber; }
		public function getRightLowAddressNumber() { return $this->rightLowAddressNumber; }
		public function getRightHighAddressNumber() { return $this->rightHighAddressNumber; }
		public function getJurisdiction_id() { return $this->jurisdiction_id; }
		public function getJurisdictionLeft_id() { return $this->jurisdictionLeft_id; }
		public function getJurisdictionRight_id() { return $this->jurisdictionRight_id; }
		public function getConstructionStatus_id() { return $this->constructionStatus_id; }
		public function getDedicatedRightOfWay() { return $this->dedicatedRightOfWay; }
		public function getMaintainedBy() { return $this->maintainedBy; }
		public function getInventoryStatus_id() { return $this->inventoryStatus_id; }
		public function getTravelWayCode_id() { return $this->travelWayCode_id; }
		public function getTravelDirection_id() { return $this->travelDirection_id; }
		public function getSpeedLimit() { return $this->speedLimit; }
		public function getSpeedLimitOrdinance() { return $this->speedLimitOrdinance; }
		public function getThoroughfareClass_id() { return $this->thoroughfareClass_id; }
		public function getSegmentBack_id() { return $this->segmentBack_id; }
		public function getSegmentAhead_id() { return $this->segmentAhead_id; }
		public function getIntersectionBack_id() { return $this->intersectionBack_id; }
		public function getIntersectionAhead_id() { return $this->intersectionAhead_id; }
		public function getLowAddressIntersection_id() { return $this->lowAddressIntersection_id; }
		public function getHighAddressIntersection_id() { return $this->highAddressIntersection_id; }
		public function getLeftSidewalkStatus_id() { return $this->leftSidewalkStatus_id; }
		public function getRightSidewalkStatus_id() { return $this->rightSidewalkStatus_id; }
		public function getMapArea() { return $this->mapArea; }
		public function getMapStatus_id() { return $this->mapStatus_id; }
		public function getMapSource() { return $this->mapSource; }
		public function getLabelX() { return $this->labelX; }
		public function getLabelY() { return $this->labelY; }
		public function getNotes() { return $this->notes; }

		public function getStatus()
		{
			if ($this->status_id)
			{
				if (!$this->status) { $this->status = new Status($this->status_id); }
				return $this->status;
			}
			else return null;
		}

		public function getDataOwner()
		{
			if ($this->dataOwner_id)
			{
				if (!$this->dataOwner) { $this->dataOwner = new User($this->dataOwner_id); }
				return $this->dataOwner;
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

		public function getJurisdictionLeft()
		{
			if ($this->jurisdictionLeft_id)
			{
				if (!$this->jurisdictionLeft) { $this->jurisdictionLeft = new Jurisdiction($this->jurisdictionLeft_id); }
				return $this->jurisdictionLeft;
			}
			else return null;
		}

		public function getJurisdictionRight()
		{
			if ($this->jurisdictionRight_id)
			{
				if (!$this->jurisdictionRight) { $this->jurisdictionRight = new Jurisdiction($this->jurisdictionRight_id); }
				return $this->jurisdictionRight;
			}
			else return null;
		}

		public function getConstructionStatus()
		{
			if ($this->constructionStatus_id)
			{
				if (!$this->constructionStatus) { $this->constructionStatus = new Status($this->constructionStatus_id); }
				return $this->constructionStatus;
			}
			else return null;
		}

		public function getInventoryStatus()
		{
			if ($this->inventoryStatus_id)
			{
				if (!$this->inventoryStatus) { $this->inventoryStatus = new Status($this->inventoryStatus_id); }
				return $this->inventoryStatus;
			}
			else return null;
		}

		public function getTravelWayCode() { return $this->getTravelWayCode_id(); }

		public function getTravelDirection()
		{
			if ($this->travelDirection_id)
			{
				if (!$this->travelDirection) { $this->travelDirection = new Direction($this->travelDirection_id); }
				return $this->travelDirection;
			}
			else return null;
		}

		public function getThoroughfareClass()
		{
			if ($this->thoroughfareClass_id)
			{
				if (!$this->thoroughfareClass) { $this->thoroughfareClass = new ThoroughfareClass($this->thoroughfareClass_id); }
				return $this->thoroughfareClass;
			}
			else return null;
		}

		public function getSegmentBack()
		{
			if ($this->segmentBack_id)
			{
				if (!$this->segmentBack) { $this->segmentBack = new Segment($this->segmentBack_id); }
				return $this->segmentBack;
			}
			else return null;
		}

		public function getSegmentAhead()
		{
			if ($this->segmentAhead_id)
			{
				if (!$this->segmentAhead) { $this->segmentAhead = new Segment($this->segmentAhead_id); }
				return $this->segmentAhead;
			}
			else return null;
		}

		public function getIntersectionBack()
		{
			if ($this->intersectionBack_id)
			{
				if (!$this->intersectionBack) { $this->intersectionBack = new Intersection($this->intersectionBack_id); }
				return $this->intersectionBack;
			}
			else return null;
		}

		public function getIntersectionAhead()
		{
			if ($this->intersectionAhead_id)
			{
				if (!$this->intersectionAhead) { $this->intersectionAhead = new Intersection($this->intersectionAhead_id); }
				return $this->intersectionAhead;
			}
			else return null;
		}

		public function getLowAddressIntersection()
		{
			if ($this->lowAddressIntersection_id)
			{
				if (!$this->lowAddressIntersection) { $this->lowAddressIntersection = new Intersection($this->lowAddressIntersection_id); }
				return $this->lowAddressIntersection;
			}
			else return null;
		}

		public function getHighAddressIntersection()
		{
			if ($this->highAddressIntersection_id)
			{
				if (!$this->highAddressIntersection) { $this->highAddressIntersection = new Intersection($this->highAddressIntersection_id); }
				return $this->highAddressIntersection;
			}
			else return null;
		}

		public function getLeftSidewalkStatus()
		{
			if ($this->leftSidewalkStatus_id)
			{
				if (!$this->leftSidewalkStatus) { $this->leftSidewalkStatus = new Status($this->leftSidewalkStatus_id); }
				return $this->leftSidewalkStatus;
			}
			else return null;
		}

		public function getRightSidewalkStatus()
		{
			if ($this->rightSidewalkStatus_id)
			{
				if (!$this->rightSidewalkStatus) { $this->rightSidewalkStatus = new Status($this->rightSidewalkStatus_id); }
				return $this->rightSidewalkStatus;
			}
			else return null;
		}

		public function getMapStatus()
		{
			if ($this->mapStatus_id)
			{
				if (!$this->mapStatus) { $this->mapStatus = new Status($this->mapStatus_id); }
				return $this->mapStatus;
			}
			else return null;
		}

		public function getAddresses()
		{
			if (!count($this->addressList)) { $this->addressList = new AddressList(array("segment_id"=>$this->id)); }
			return $this->addressList;
		}

		public function getStreets()
		{
			if (!count($this->streets)) { $this->streets = new StreetList(array("segment_id"=>$this->id)); }
			return $this->streets;
		}

		public function getPlaces()
		{
			if (!count($this->placeList)) { $this->placeList = new PlaceList(array("segment_id"=>$this->id)); }
			return $this->placeList;
		}

		public function setTag($string) { $this->tag = $this->sanitizeString($string); }
		public function setStatus_id($int) { $this->status = new Status($int); $this->status_id = $int; }
		public function setDataOwner_id($int) { $this->dataOwner = new User($int); $this->dataOwner_id = $int; }
		public function setDescription($string) { $this->description = $this->sanitizeString($string); }
		public function setStation($int) { $this->station = ereg_replace("[^0-9]","",$int); }
		public function setLowAddressNumber($int) { $this->lowAddressNumber = ereg_replace("[^0-9]","",$int); }
		public function setHighAddressNumber($int) { $this->highAddressNumber = ereg_replace("[^0-9]","",$int); }
		public function setLeftLowAddressNumber($int) { $this->leftLowAddressNumber = ereg_replace("[^0-9]","",$int); }
		public function setLeftHighAddressNumber($int) { $this->leftHighAddressNumber = ereg_replace("[^0-9]","",$int); }
		public function setRightLowAddressNumber($int) { $this->rightLowAddressNumber = ereg_replace("[^0-9]","",$int); }
		public function setRightHighAddressNumber($int) { $this->rightHighAddressNumber = ereg_replace("[^0-9]","",$int); }
		public function setJurisdiction_id($int) { $this->jurisdiction = new Jurisdiction($int); $this->jurisdiction_id = $int; }
		public function setJurisdictionLeft_id($int) { $this->jurisdictionLeft = new Jurisdiction($int); $this->jurisdictionLeft_id = $int; }
		public function setJurisdictionRight_id($int) { $this->jurisdictionRight = new Jurisdiction($int); $this->jurisdictionRight_id = $int; }
		public function setConstructionStatus_id($int) { $this->constructionStatus = new Status($int); $this->constructionStatus_id = $int; }
		public function setDedicatedRightOfWay($string) { $this->dedicatedRightOfWay = $this->sanitizeString($string); }
		public function setMaintainedBy($string) { $this->maintainedBy = $this->sanitizeString($string); }
		public function setInventoryStatus_id($int) { $this->inventoryStatus = new Status($int); $this->inventoryStatus_id = $int; }
		public function setTravelWayCode_id($int) { $this->travelWayCode_id = ereg_replace("[^0-9]","",$int); }
		public function setTravelDirection_id($int) { $this->travelDirection = new Direction($int); $this->travelDirection_id = $int; }
		public function setSpeedLimit($int) { $this->speedLimit = ereg_replace("[^0-9]","",$int); }
		public function setSpeedLimitOrdinance($string) { $this->speedLimitOrdinance = $this->sanitizeString($string); }
		public function setThoroughfareClass_id($int) { $this->thoroughfareClass = new ThoroughfareClass($int); $this->thoroughfareClass_id = $int; }
		public function setSegmentBack_id($int) { $this->segmentBack = new Segment($int); $this->segmentBack_id = $int; }
		public function setSegmentAhead_id($int) { $this->segmentAhead = new Segment($int); $this->segmentAhead_id = $int; }
		public function setIntersectionBack_id($int) { $this->intersectionBack = new Intersection($int); $this->intersectionBack_id = $int; }
		public function setIntersectionAhead_id($int) { $this->intersectionAhead = new Intersection($int); $this->intersectionAhead_id = $int; }
		public function setLowAddressIntersection_id($int) { $this->lowAddressIntersection = new Intersection($int); $this->lowAddressIntersection_id = $int; }
		public function setHighAddressIntersection_id($int) { $this->highAddressIntersection = new Intersection($int); $this->highAddressIntersection_id = $int; }
		public function setLeftSidewalkStatus_id($int) { $this->leftSidewalkStatus = new Status($int); $this->leftSidewalkStatus_id = $int; }
		public function setRightSidewalkStatus_id($int) { $this->rightSidewalkStatus = new Status($int); $this->rightSidewalkStatus_id = $int; }
		public function setMapArea($char) { $this->mapArea = $char; }
		public function setMapStatus_id($int) { $this->mapStatus = new Status($int); $this->mapStatus_id = $int; }
		public function setMapSource($string) { $this->mapSource = $this->sanitizeString($string); }
		public function setLabelX($int) { $this->labelX = ereg_replace("[^0-9]","",$int); }
		public function setLabelY($int) { $this->labelY = ereg_replace("[^0-9]","",$int); }
		public function setNotes($text) { $this->notes = $text; }

		public function setStatus($status) { $this->status_id = $status->getId(); $this->property = $status; }
		public function setDataOwner($user) { $this->dataOwner_id = $user->getId(); $this->property = $user; }
		public function setJurisdiction($jurisdiction) { $this->jurisdiction_id = $jurisdiction->getId(); $this->property = $jurisdiction; }
		public function setJurisdictionLeft($jurisdiction) { $this->jurisdictionLeft_id = $jurisdiction->getId(); $this->property = $jurisdiction; }
		public function setJurisdictionRight($jurisdiction) { $this->jurisdictionRight_id = $jurisdiction->getId(); $this->property = $jurisdiction; }
		public function setConstructionStatus($status) { $this->constructionStatus_id = $status->getId(); $this->property = $status; }
		public function setInventoryStatus($status) { $this->inventoryStatus_id = $status->getId(); $this->property = $status; }
		public function setTravelWayCode($int) { $this->travelWayCode_id = ereg_replace("[^0-9]","",$int); }
		public function setTravelDirection($direction) { $this->travelDirection_id = $direction->getId(); $this->property = $direction; }
		public function setThoroughfareClass($thoroughfareClass) { $this->thoroughfareClass_id = $thoroughfareClass->getId(); $this->property = $thoroughfareClass; }
		public function setSegmentBack($segment) { $this->segmentBack_id = $segment->getId(); $this->property = $segment; }
		public function setSegmentAhead($segment) { $this->segmentAhead_id = $segment->getId(); $this->property = $segment; }
		public function setIntersectionBack($intersection) { $this->intersectionBack_id = $intersection->getId(); $this->property = $intersection; }
		public function setIntersectionAhead($intersection) { $this->intersectionAhead_id = $intersection->getId(); $this->property = $intersection; }
		public function setLowAddressIntersection($intersection) { $this->lowAddressIntersection_id = $intersection->getId(); $this->property = $intersection; }
		public function setHighAddressIntersection($intersection) { $this->highAddressIntersection_id = $intersection->getId(); $this->property = $intersection; }
		public function setLeftSidewalkStatus($status) { $this->leftSidewalkStatus_id = $status->getId(); $this->property = $status; }
		public function setRightSidewalkStatus($status) { $this->rightSidewalkStatus_id = $status->getId(); $this->property = $status; }
		public function setMapStatus($status) { $this->mapStatus_id = $status->getId(); $this->property = $status; }
	}
?>