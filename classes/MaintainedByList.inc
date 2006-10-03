<?php
/**
* @copyright Copyright (C) 2006 City of Bloomington, Indiana. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
*/
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