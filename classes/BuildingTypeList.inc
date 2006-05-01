<?php
	require_once(GLOBAL_INCLUDES."/classes/PDOResultIterator.inc");
	require_once(APPLICATION_HOME."/classes/BuildingType.inc");

	class BuildingTypeList extends PDOResultIterator
	{

		public function find($fields=null,$sort="typeID")
		{
			global $PDO;

			$options = array();
			if (isset($fields['typeID'])) { $options[] = "typeID='$fields[typeID]'"; }
			if (isset($fields['description'])) { $options[] = "description='$fields[description]'"; }

			if (count($options)) { $where = " where ".implode(" and ",$options); } else { $where = ""; }
			$sql = "select typeID from buildingTypes $where order by $sort";

			$result = $PDO->query($sql);
			if ($result)
			{
				foreach($result as $row) { $this->list[] = $row['typeID']; }
			}
			else { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
		}


		protected function loadCurrent() { return new BuildingType(current($this->list)); }
	}
?>