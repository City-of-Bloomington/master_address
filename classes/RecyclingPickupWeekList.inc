<?php
	class RecyclingPickupWeekList extends PDOResultIterator
	{
		public function __construct($fields=null) { if (is_array($fields)) $this->find($fields); }

		public function find($fields=null,$sort="week")
		{
			global $PDO;

			$options = array();
			if (isset($fields['id'])) { $options[] = "id=$fields[id]"; }
			if (isset($fields['week'])) { $options[] = "week='$fields[week]'"; }

			if (count($options)) { $where = " where ".implode(" and ",$options); } else { $where = ""; }
			$sql = "select id from recyclingPickupWeeks $where order by $sort";

			$result = $PDO->query($sql);
			if ($result)
			{
				foreach($result as $row) { $this->list[] = $row['id']; }
			}
			else { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
		}


		protected function loadResult($key) { return new RecyclingPickupWeek($this->list[$key]); }
	}
?>