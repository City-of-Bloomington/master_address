<?php
	require_once(GLOBAL_INCLUDES."/classes/PDOResultIterator.inc");
	require_once(APPLICATION_HOME."/classes/Place.inc");

	class PlaceList extends PDOResultIterator
	{

		public function find($fields=null,$sort="id")
		{
			global $PDO;

			$options = array();
			if (isset($fields['id'])) { $options[] = "id=$fields[id]"; }
			if (isset($fields['name'])) { $options[] = "name='$fields[name]'"; }
			if (isset($fields['township_id'])) { $options[] = "township_id=$fields[township_id]"; }
			if (isset($fields['jurisdiction_id'])) { $options[] = "jurisdiction_id=$fields[jurisdiction_id]"; }
			if (isset($fields['trashPickupDay_id'])) { $options[] = "trashPickupDay_id=$fields[trashPickupDay_id]"; }
			if (isset($fields['trashLargeItemPickupDay_id'])) { $options[] = "trashLargeItemPickupDay_id=$fields[trashLargeItemPickupDay_id]"; }
			if (isset($fields['recyclingPickupWeek_id'])) { $options[] = "recyclingPickupWeek_id=$fields[recyclingPickupWeek_id]"; }
			if (isset($fields['mailable'])) { $options[] = "mailable='$fields[mailable]'"; }
			if (isset($fields['livable'])) { $options[] = "livable='$fields[livable]'"; }
			if (isset($fields['section'])) { $options[] = "section='$fields[section]'"; }
			if (isset($fields['quarterSection'])) { $options[] = "quarterSection='$fields[quarterSection]'"; }
			if (isset($fields['class'])) { $options[] = "class='$fields[class]'"; }

			if (count($options)) { $where = " where ".implode(" and ",$options); } else { $where = ""; }
			$sql = "select id from places $where order by $sort";

			$result = $PDO->query($sql);
			if ($result)
			{
				foreach($result as $row) { $this->list[] = $row['id']; }
			}
			else { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
		}


		protected function loadResult($key) { return new Place($this->list[$key]); }
	}
?>