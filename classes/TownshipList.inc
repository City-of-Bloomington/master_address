<?php
	require_once(GLOBAL_INCLUDES."/classes/PDOResultIterator.inc");
	require_once(APPLICATION_HOME."/classes/Township.inc");

	class TownshipList extends PDOResultIterator
	{

		public function find($fields=null,$sort="townshipID")
		{
			global $PDO;

			$options = array();
			if (isset($fields['townshipID'])) { $options[] = "townshipID=$fields[townshipID]"; }
			if (isset($fields['name'])) { $options[] = "name='$fields[name]'"; }
			if (isset($fields['abbreviation'])) { $options[] = "abbreviation='$fields[abbreviation]'"; }
			if (isset($fields['quarterCode'])) { $options[] = "quarterCode='$fields[quarterCode]'"; }

			if (count($options)) { $where = " where ".implode(" and ",$options); } else { $where = ""; }
			$sql = "select townshipID from townships $where order by $sort";

			foreach($PDO->query($sql) as $row) { $this->list[] = $row['townshipID']; }
		}


		protected function loadCurrent() { return new Township(current($this->list)); }
	}
?>