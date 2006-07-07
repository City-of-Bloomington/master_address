<?php
	class CompleteList extends PDOResultIterator
	{
		public function find($fields="null",$sort="complete")
		{
			global $PDO;

			$sql = "select distinct complete from segments order by $sort";
			foreach($PDO->query($sql) as $row) { $this->list[] = $row['complete']; }
		}

		protected function loadResult($key) { return $this->list[$key]; }
	}
?>