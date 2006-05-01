<?php
	require_once(GLOBAL_INCLUDES."/classes/PDOResultIterator.inc");
	require_once(APPLICATION_HOME."/classes/District.inc");

	class DistrictList extends PDOResultIterator
	{

		public function find($fields=null,$sort="districtID")
		{
			global $PDO;

			$options = array();
			if (isset($fields['districtID'])) { $options[] = "districtID='$fields[districtID]'"; }
			if (isset($fields['name'])) { $options[] = "name='$fields[name]'"; }
			if (isset($fields['districtTypeID'])) { $options[] = "districtTypeID='$fields[districtTypeID]'"; }

			if (count($options)) { $where = " where ".implode(" and ",$options); } else { $where = ""; }
			$sql = "select districtID from districts $where order by $sort";

			$result = $PDO->query($sql);
			if ($result)
			{
				foreach($result as $row) { $this->list[] = $row['districtID']; }
			}
			else { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
		}


		protected function loadCurrent() { return new District(current($this->list)); }
	}
?>