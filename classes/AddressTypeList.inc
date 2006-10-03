<?php
/**
* @copyright Copyright (C) 2006 City of Bloomington, Indiana. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
*/
	class AddressTypeList extends PDOResultIterator
	{
		public function find($fields=null,$sort="addressType")
		{
			global $PDO;

			$sql = "select distinct addressType from addresses order by $sort";
			foreach($PDO->query($sql) as $row) { $this->list[] = $row['addressType']; }
		}

		protected function loadResult($key) { return $this->list[$key]; }
	}
?>