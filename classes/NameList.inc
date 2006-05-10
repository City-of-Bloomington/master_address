<?php
	require_once(GLOBAL_INCLUDES."/classes/PDOResultIterator.inc");
	require_once(APPLICATION_HOME."/classes/Name.inc");

	class NameList extends PDOResultIterator
	{

		public function find($fields=null,$sort="id")
		{
			global $PDO;

			$options = array();
			if (isset($fields['id'])) { $options[] = "id=$fields[id]"; }
			if (isset($fields['town_id'])) { $options[] = "town_id=$fields[town_id]"; }
			if (isset($fields['direction_id'])) { $options[] = "direction_id=$fields[direction_id]"; }
			if (isset($fields['name'])) { $options[] = "name='$fields[name]'"; }
			if (isset($fields['suffix_id'])) { $options[] = "suffix_id=$fields[suffix_id]"; }
			if (isset($fields['postDirection_id'])) { $options[] = "postDirection_id=$fields[postDirection_id]"; }
			if (isset($fields['startDate'])) { $options[] = "startDate='$fields[startDate]'"; }
			if (isset($fields['endDate'])) { $options[] = "endDate='$fields[endDate]'"; }
			if (isset($fields['notes'])) { $options[] = "notes='$fields[notes]'"; }

			if (count($options)) { $where = " where ".implode(" and ",$options); } else { $where = ""; }
			$sql = "select id from names $where order by $sort";

			$result = $PDO->query($sql);
			if ($result)
			{
				foreach($result as $row) { $this->list[] = $row['id']; }
			}
			else { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
		}


		protected function loadCurrent() { return new Name(current($this->list)); }
	}
?>