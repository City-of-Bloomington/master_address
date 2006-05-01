<?php
	require_once(GLOBAL_INCLUDES."/classes/PDOResultIterator.inc");
	require_once(APPLICATION_HOME."/classes/Direction.inc");

	class DirectionList extends PDOResultIterator
	{

		public function find($fields=null,$sort="directionCode")
		{
			global $PDO;

			$options = array();
			if (isset($fields['directionCode'])) { $options[] = "directionCode='$fields[directionCode]'"; }
			if (isset($fields['direction'])) { $options[] = "direction='$fields[direction]'"; }

			if (count($options)) { $where = " where ".implode(" and ",$options); } else { $where = ""; }
			$sql = "select directionCode from directions $where order by $sort";

			$result = $PDO->query($sql);
			if ($result)
			{
				foreach($result as $row) { $this->list[] = $row['directionCode']; }
			}
			else { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
		}


		protected function loadCurrent() { return new Direction(current($this->list)); }
	}
?>