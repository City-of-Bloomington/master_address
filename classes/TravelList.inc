<?php
	class TravelList extends PDOResultIterator
	{
		public function find($fields=null,$sort="travel")
		{
			global $PDO;

			$sql = "select distinct travel from segments order by $sort";
			foreach($PDO->query($sql) as $row) { $this->list[] = $row['travel']; }
		}

		protected function loadResult($key) { return $this->list[$key]; }
	}
?>