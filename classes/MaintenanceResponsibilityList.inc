<?php
	class MaintenanceResponsibilityList extends PDOResultIterator
	{
		public function find($fields=null,$sort="maintenanceResponsibility")
		{
			global $PDO;

			$sql = "select distinct maintenanceResponsibility from segments order by $sort";
			foreach($PDO->query($sql) as $row) { $this->list[] = $row['maintenanceResponsibility']; }
		}

		protected function loadResult($key) { return $this->list[$key]; }
	}
?>