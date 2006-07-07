<?php
	class TransportationClassList extends PDOResultIterator
	{
		public function find($fields=null,$sort="transportationClass")
		{
			global $PDO;

			$sql = "select distinct transportationClass from segments order by $sort";
			foreach($PDO->query($sql) as $row) { $this->list[] = $row['transportationClass']; }
		}

		protected function loadResult($key) { return $this->list[$key]; }
	}
?>