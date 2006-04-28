<?php
	require_once(GLOBAL_INCLUDES."/classes/PDOResultIterator.inc");
	require_once(APPLICATION_HOME."/classes/Town.inc");

	class TownList extends PDOResultIterator
	{

		public function find($fields=null,$sort="name")
		{
			global $PDO;

			$options = array();
			if (isset($fields['townID'])) { $options[] = "townID='$fields[townID]'"; }
			if (isset($fields['name'])) { $options[] = "name='$fields[name]'"; }

			if (count($options)) { $where = " where ".implode(" and ",$options); } else { $where = ""; }
			$sql = "select townID from towns $where order by $sort";

			foreach($PDO->query($sql) as $row) { $this->list[] = $row['townID']; }
		}


		protected function loadCurrent() { return new Town(current($this->list)); }
	}
?>