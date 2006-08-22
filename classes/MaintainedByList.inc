<?php
	class MaintainedByList extends PDOResultIterator
	{
		public function find($fields=null,$sort="maintainedBy")
		{
			global $PDO;

			$sql = "select distinct maintainedBy from segments order by $sort";
			foreach($PDO->query($sql) as $row) { $this->list[] = $row['maintainedBy']; }
		}

		protected function loadResult($key) { return $this->list[$key]; }
	}
?>