<?php
	require_once(GLOBAL_INCLUDES."/classes/PDOResultIterator.inc");
	require_once(APPLICATION_HOME."/classes/DistrictType.inc");

	class DistrictTypeList extends PDOResultIterator
	{

		public function find($fields=null,$sort="districtTypeID")
		{
			global $PDO;

			$options = array();
			if (isset($fields['districtTypeID'])) { $options[] = "districtTypeID=$fields[districtTypeID]"; }
			if (isset($fields['type'])) { $options[] = "type='$fields[type]'"; }

			if (count($options)) { $where = " where ".implode(" and ",$options); } else { $where = ""; }
			$sql = "select districtTypeID from districtTypes $where order by $sort";

			$result = $PDO->query($sql);
			if ($result)
			{
				foreach($result as $row) { $this->list[] = $row['districtTypeID']; }
			}
			else { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
		}


		protected function loadCurrent() { return new DistrictType(current($this->list)); }
	}
?>