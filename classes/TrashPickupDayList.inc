<?php
	require_once(GLOBAL_INCLUDES."/classes/PDOResultIterator.inc");

	class TrashPickupDayList extends PDOResultIterator
	{

		public function find($fields=null,$sort="trashPickupDay")
		{
			global $PDO;

			$options = array();
			if (isset($fields['trashPickupDay'])) { $options[] = "trashPickupDay='$fields[trashPickupDay]'"; }

			if (count($options)) { $where = " where ".implode(" and ",$options); } else { $where = ""; }
			$sql = "select trashPickupDay from trashPickupDays $where order by $sort";

			$result = $PDO->query($sql);
			if ($result)
			{
				foreach($result as $row) { $this->list[] = $row['trashPickupDay']; }
			}
			else { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
		}


		protected function loadResult($key) { return $this->list[$key]; }
	}
?>