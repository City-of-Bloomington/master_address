<?php
	require_once(GLOBAL_INCLUDES."/classes/PDOResultIterator.inc");
	require_once(APPLICATION_HOME."/classes/Jurisdiction.inc");

	class JurisdictionList extends PDOResultIterator
	{

		public function find($fields=null,$sort="jurisdictionID")
		{
			global $PDO;

			$options = array();
			if (isset($fields['jurisdictionID'])) { $options[] = "jurisdictionID=$fields[jurisdictionID]"; }
			if (isset($fields['name'])) { $options[] = "name='$fields[name]'"; }

			if (count($options)) { $where = " where ".implode(" and ",$options); } else { $where = ""; }
			$sql = "select jurisdictionID from jurisdictions $where order by $sort";

			foreach($PDO->query($sql) as $row) { $this->list[] = $row['jurisdictionID']; }
		}


		protected function loadCurrent() { return new Jurisdiction(current($this->list)); }
	}
?>