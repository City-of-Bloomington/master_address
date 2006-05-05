<?php
	require_once(GLOBAL_INCLUDES."/classes/PDOResultIterator.inc");
	require_once(APPLICATION_HOME."/classes/Plat.inc");

	class PlatList extends PDOResultIterator
	{

		public function find($fields=null,$sort="platID")
		{
			global $PDO;

			$options = array();
			if (isset($fields['platID'])) { $options[] = "platID=$fields[platID]"; }
			if (isset($fields['name'])) { $options[] = "name='$fields[name]'"; }
			if (isset($fields['townshipID'])) { $options[] = "townshipID=$fields[townshipID]"; }
			if (isset($fields['type'])) { $options[] = "type='$fields[type]'"; }
			if (isset($fields['cabinet'])) { $options[] = "cabinet='$fields[cabinet]'"; }
			if (isset($fields['envelope'])) { $options[] = "envelope='$fields[envelope]'"; }
			if (isset($fields['notes'])) { $options[] = "notes='$fields[notes]'"; }

			if (count($options)) { $where = " where ".implode(" and ",$options); } else { $where = ""; }
			$sql = "select platID from plats $where order by $sort";

			$result = $PDO->query($sql);
			if ($result)
			{
				foreach($result as $row) { $this->list[] = $row['platID']; }
			}
			else { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
		}


		protected function loadCurrent() { return new Plat(current($this->list)); }
	}
?>