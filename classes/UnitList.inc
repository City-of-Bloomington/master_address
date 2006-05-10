<?php
	require_once(GLOBAL_INCLUDES."/classes/PDOResultIterator.inc");
	require_once(APPLICATION_HOME."/classes/Unit.inc");

	class UnitList extends PDOResultIterator
	{

		public function find($fields=null,$sort="id")
		{
			global $PDO;

			$options = array();
			if (isset($fields['id'])) { $options[] = "id=$fields[id]"; }
			if (isset($fields['place_id'])) { $options[] = "place_id=$fields[place_id]"; }
			if (isset($fields['unitType_id'])) { $options[] = "unitType_id=$fields[unitType_id]"; }
			if (isset($fields['building_id'])) { $options[] = "building_id=$fields[building_id]"; }
			if (isset($fields['identifier'])) { $options[] = "identifier='$fields[identifier]'"; }
			if (isset($fields['mailable'])) { $options[] = "mailable='$fields[mailable]'"; }
			if (isset($fields['livable'])) { $options[] = "livable='$fields[livable]'"; }
			if (isset($fields['notes'])) { $options[] = "notes='$fields[notes]'"; }

			if (count($options)) { $where = " where ".implode(" and ",$options); } else { $where = ""; }
			$sql = "select id from units $where order by $sort";

			$result = $PDO->query($sql);
			if ($result)
			{
				foreach($result as $row) { $this->list[] = $row['id']; }
			}
			else { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
		}


		protected function loadCurrent() { return new Unit(current($this->list)); }
	}
?>