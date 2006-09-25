<?php
	class QuarterSectionList extends PDOResultIterator
	{
		public function find($fields=null,$sort="quarterSection")
		{
			global $PDO;

			$sql = "select distinct quarterSection from places order by $sort";
			foreach($PDO->query($sql) as $row) { $this->list[] = $row['quarterSection']; }
		}

		protected function loadResult($key) { return $this->list[$key]; }
	}
?>