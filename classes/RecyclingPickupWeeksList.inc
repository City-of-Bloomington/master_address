<?php
	require_once(GLOBAL_INCLUDES."/classes/PDOResultIterator.inc");

	class RecyclingPickupWeekList extends PDOResultIterator
	{

		public function find($fields=null,$sort="recyclingPickupWeek")
		{
			global $PDO;

			$options = array();
			if (isset($fields['recyclingPickupWeek'])) { $options[] = "recyclingPickupWeek='$fields[recyclingPickupWeek]'"; }

			if (count($options)) { $where = " where ".implode(" and ",$options); } else { $where = ""; }
			$sql = "select recyclingPickupWeek from recyclingPickupWeeks $where order by $sort";

			$result = $PDO->query($sql);
			if ($result)
			{
				foreach($result as $row) { $this->list[] = $row['recyclingPickupWeek']; }
			}
			else { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
		}


		protected function loadCurrent() { return current($this->list); }
	}
?>