<?php
/**
 * @copyright Copyright (C) 2006,2007 City of Bloomington, Indiana. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
	class QuarterSectionList extends PDOResultIterator
	{
		public function find($fields=null,$sort="quarterSection",$limit=null,$groupBy=null)
		{
			global $PDO;

			$this->sort = $sort;
			$this->limit = $limit;
			$this->groupBy = $groupBy;

			$sql = "select distinct quarterSection from places order by {$this->sort}";
			foreach($PDO->query($sql) as $row) { $this->list[] = $row['quarterSection']; }
		}

		protected function loadResult($key) { return $this->list[$key]; }
	}
?>