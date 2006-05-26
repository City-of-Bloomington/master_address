<?php
	require_once(GLOBAL_INCLUDES."/classes/PDOResultIterator.inc");
	require_once(APPLICATION_HOME."/classes/Segment.inc");

	class SegmentList extends PDOResultIterator
	{
		private $joins = "";
		private $where = "";
		private $sort;

		public function find($fields=null,$sort="id")
		{
			global $PDO;

			$this->sort = $sort;

			$options = array();
			if (isset($fields['id'])) { $options[] = "id=$fields[id]"; }
			if (isset($fields['street_id'])) { $options[] = "street_id=$fields[street_id]"; }
			if (isset($fields['tag'])) { $options[] = "tag='$fields[tag]'"; }
			if (isset($fields['startingNumber'])) { $options[] = "startingNumber=$fields[startingNumber]"; }
			if (isset($fields['endingNumber'])) { $options[] = "endingNumber=$fields[endingNumber]"; }
			if (isset($fields['jurisdiction_id'])) { $options[] = "jurisdiction_id=$fields[jurisdiction_id]"; }
			if (isset($fields['status_id'])) { $options[] = "status_id=$fields[status_id]"; }
			if (isset($fields['notes'])) { $options[] = "notes='$fields[notes]'"; }
			if (isset($fields['speedLimit'])) { $options[] = "speedLimit=$fields[speedLimit]"; }
			if (isset($fields['INDOTSegmentID'])) { $options[] = "INDOTSegmentID='$fields[INDOTSegmentID]'"; }
			if (isset($fields['transportationClass'])) { $options[] = "transportationClass='$fields[transportationClass]'"; }
			if (isset($fields['maintenanceResponsibility'])) { $options[] = "maintenanceResponsibility='$fields[maintenanceResponsibility]'"; }
			if (isset($fields['leftLowNumber'])) { $options[] = "leftLowNumber=$fields[leftLowNumber]"; }
			if (isset($fields['leftHighNumber'])) { $options[] = "leftHighNumber=$fields[leftHighNumber]"; }
			if (isset($fields['rightLowNumber'])) { $options[] = "rightLowNumber=$fields[rightLowNumber]"; }
			if (isset($fields['rightHighNumber'])) { $options[] = "rightHighNumber=$fields[rightHighNumber]"; }
			if (isset($fields['rcinode1'])) { $options[] = "rcinode1='$fields[rcinode1]'"; }
			if (isset($fields['rcinode2'])) { $options[] = "rcinode2='$fields[rcinode2]'"; }
			if (isset($fields['lowNode'])) { $options[] = "lowNode='$fields[lowNode]'"; }
			if (isset($fields['highNode'])) { $options[] = "highNode='$fields[highNode]'"; }
			if (isset($fields['lowXCoordinate'])) { $options[] = "lowXCoordinate=$fields[lowXCoordinate]"; }
			if (isset($fields['lowYCoordinate'])) { $options[] = "lowYCoordinate=$fields[lowYCoordinate]"; }
			if (isset($fields['highXCoordinate'])) { $options[] = "highXCoordinate=$fields[highXCoordinate]"; }
			if (isset($fields['highYCoordinate'])) { $options[] = "highYCoordinate=$fields[highYCoordinate]"; }
			if (isset($fields['travel'])) { $options[] = "travel='$fields[travel]'"; }
			if (isset($fields['travelDirection_id'])) { $options[] = "travelDirection_id=$fields[travelDirection_id]"; }
			if (isset($fields['complete'])) { $options[] = "complete='$fields[complete]'"; }
			if (isset($fields['rclBack'])) { $options[] = "rclBack='$fields[rclBack]'"; }
			if (isset($fields['rclNext'])) { $options[] = "rclNext='$fields[rclNext]'"; }
			if (isset($fields['classRow'])) { $options[] = "classRow='$fields[classRow]'"; }
			if (isset($fields['mapArea'])) { $options[] = "mapArea='$fields[mapArea]'"; }
			if (isset($fields['lastUpdatedDate'])) { $options[] = "lastUpdatedDate='$fields[lastUpdatedDate]'"; }
			if (isset($fields['lastUpdatedAction'])) { $options[] = "lastUpdatedAction='$fields[lastUpdatedAction]'"; }
			if (isset($fields['lastUpdatedUser_id'])) { $options[] = "lastUpdatedUser_id=$fields[lastUpdatedUser_id]"; }

			if (isset($fields['number'])) { $options[] = "startingNumber<='$fields[number]' and endingNumber>='$fields[number]'"; }


			if (count($options)) { $this->where = "where ".implode(" and ",$options); }

			$sql = "select id from segments {$this->joins} {$this->where} order by {$this->sort}";
			$result = $PDO->query($sql);
			if ($result)
			{
				foreach($result as $row) { $this->list[] = $row['id']; }
			}
			else { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
		}


		protected function loadResult($key) { return new Segment($this->list[$key]); }
	}
?>