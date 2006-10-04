<?php
/**
 * @copyright Copyright (C) 2006 City of Bloomington, Indiana. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 */
	class PlaceClassList extends PDOResultIterator
	{
		public function __construct($fields=null,$sort='class')
		{
			$this->select = "select distinct class as id from places";
			$this->sort = $sort;
		}

		public function find($fields=null,$sort='class')
		{
			$this->sort = $sort;

			$this->populateList();
		}

		protected function loadResult($key) { return $this->list[$key]; }
	}
?>