<?php
/**
* @copyright Copyright (C) 2006 City of Bloomington, Indiana. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
*/
	class SegmentList extends PDOResultIterator
	{
		public function __construct($fields=null,$sort="lowAddressNumber")
		{
			$this->select = "select segments.id from segments";
			$this->sort = $sort;
			if (is_array($fields)) $this->find($fields);
		}


		public function find($fields=null,$sort="lowAddressNumber")
		{
			$this->sort = $sort;

			$options = array();
			if (isset($fields['id'])) { $options[] = "id='$fields[id]'"; }
			if (isset($fields['tag'])) { $options[] = "tag='$fields[tag]'"; }
			if (isset($fields['segmentStatus_id'])) { $options[] = "segmentStatus_id='$fields[segmentStatus_id]'"; }
			if (isset($fields['dataOwner_id'])) { $options[] = "dataOwner_id='$fields[dataOwner_id]'"; }
			if (isset($fields['description'])) { $options[] = "description='$fields[description]'"; }
			if (isset($fields['station'])) { $options[] = "station='$fields[station]'"; }
			if (isset($fields['lowAddressNumber'])) { $options[] = "lowAddressNumber='$fields[lowAddressNumber]'"; }
			if (isset($fields['highAddressNumber'])) { $options[] = "highAddressNumber='$fields[highAddressNumber]'"; }
			if (isset($fields['leftLowAddressNumber'])) { $options[] = "leftLowAddressNumber='$fields[leftLowAddressNumber]'"; }
			if (isset($fields['leftHighAddressNumber'])) { $options[] = "leftHighAddressNumber='$fields[leftHighAddressNumber]'"; }
			if (isset($fields['rightLowAddressNumber'])) { $options[] = "rightLowAddressNumber='$fields[rightLowAddressNumber]'"; }
			if (isset($fields['rightHighAddressNumber'])) { $options[] = "rightHighAddressNumber='$fields[rightHighAddressNumber]'"; }
			if (isset($fields['jurisdiction_id'])) { $options[] = "jurisdiction_id='$fields[jurisdiction_id]'"; }
			if (isset($fields['jurisdictionLeft_id'])) { $options[] = "jurisdictionLeft_id='$fields[jurisdictionLeft_id]'"; }
			if (isset($fields['jurisdictionRight_id'])) { $options[] = "jurisdictionRight_id='$fields[jurisdictionRight_id]'"; }
			if (isset($fields['constructionStatus_id'])) { $options[] = "constructionStatus_id='$fields[constructionStatus_id]'"; }
			if (isset($fields['dedicatedRightOfWay'])) { $options[] = "dedicatedRightOfWay='$fields[dedicatedRightOfWay]'"; }
			if (isset($fields['maintainedBy'])) { $options[] = "maintainedBy='$fields[maintainedBy]'"; }
			if (isset($fields['inventoryStatus_id'])) { $options[] = "inventoryStatus_id='$fields[inventoryStatus_id]'"; }
			if (isset($fields['travelWayCode_id'])) { $options[] = "travelWayCode_id='$fields[travelWayCode_id]'"; }
			if (isset($fields['travelDirection_id'])) { $options[] = "travelDirection_id='$fields[travelDirection_id]'"; }
			if (isset($fields['speedLimit'])) { $options[] = "speedLimit='$fields[speedLimit]'"; }
			if (isset($fields['speedLimitOrdinance'])) { $options[] = "speedLimitOrdinance='$fields[speedLimitOrdinance]'"; }
			if (isset($fields['thoroughfareClass_id'])) { $options[] = "thoroughfareClass_id='$fields[thoroughfareClass_id]'"; }
			if (isset($fields['segmentBack_id'])) { $options[] = "segmentBack_id='$fields[segmentBack_id]'"; }
			if (isset($fields['segmentAhead_id'])) { $options[] = "segmentAhead_id='$fields[segmentAhead_id]'"; }
			if (isset($fields['intersectionBack_id'])) { $options[] = "intersectionBack_id='$fields[intersectionBack_id]'"; }
			if (isset($fields['intersectionAhead_id'])) { $options[] = "intersectionAhead_id='$fields[intersectionAhead_id]'"; }
			if (isset($fields['lowAddressIntersection_id'])) { $options[] = "lowAddressIntersection_id='$fields[lowAddressIntersection_id]'"; }
			if (isset($fields['highAddressIntersection_id'])) { $options[] = "highAddressIntersection_id='$fields[highAddressIntersection_id]'"; }
			if (isset($fields['leftSidewalkStatus_id'])) { $options[] = "leftSidewalkStatus_id='$fields[leftSidewalkStatus_id]'"; }
			if (isset($fields['rightSidewalkStatus_id'])) { $options[] = "rightSidewalkStatus_id='$fields[rightSidewalkStatus_id]'"; }
			if (isset($fields['mapArea'])) { $options[] = "mapArea='$fields[mapArea]'"; }
			if (isset($fields['mapStatus_id'])) { $options[] = "mapStatus_id='$fields[mapStatus_id]'"; }
			if (isset($fields['mapSource'])) { $options[] = "mapSource='$fields[mapSource]'"; }
			if (isset($fields['labelX'])) { $options[] = "labelX='$fields[labelX]'"; }
			if (isset($fields['labelY'])) { $options[] = "labelY='$fields[labelY]'"; }
			if (isset($fields['notes'])) { $options[] = "notes='$fields[notes]'"; }

			if (isset($fields['number'])) { $options[] = "lowAddressNumber<='$fields[number]' and highAddressNumber>='$fields[number]'"; }

			# Finding on fields from other tables required joining those tables.
			# You can add fields from other tables to $options by adding the join SQL
			# to $this->joins here
			if (isset($fields['street_id']))
			{
				$this->joins.=" left join street_segments on segment_id=segments.id";
				if ($fields['street_id'] != "null") { $options[] = "street_id=$fields[street_id]";}
				else { $options[] = "street_id is null"; }
			}

			$this->populateList($options);
		}


		protected function loadResult($key) { return new Segment($this->list[$key]); }
	}
?>