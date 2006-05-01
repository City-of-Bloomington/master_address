<?php
	require_once(GLOBAL_INCLUDES."/classes/PDOResultIterator.inc");
	require_once(APPLICATION_HOME."/classes/UnitType.inc");

	class UnitTypeList extends PDOResultIterator
	{

		public function find($fields=null,$sort="unitType")
		{
			global $PDO;

			$options = array();
			if (isset($fields['unitType'])) { $options[] = "unitType='$fields[unitType]'"; }
			if (isset($fields['description'])) { $options[] = "description='$fields[description]'"; }

			if (count($options)) { $where = " where ".implode(" and ",$options); } else { $where = ""; }
			$sql = "select unitType from unitTypes $where order by $sort";

			$result = $PDO->query($sql);
			if ($result)
			{
				foreach($result as $row) { $this->list[] = $row['unitType']; }
			}
			else { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
		}


		protected function loadCurrent() { return new UnitType(current($this->list)); }
	}
?>