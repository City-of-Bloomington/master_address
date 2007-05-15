<?php
/**
 * @copyright Copyright (C) 2006,2007 City of Bloomington, Indiana. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
	class StatusList extends PDOResultIterator
	{
		private $type;
		private $tableName;

		public function __construct($type,$fields=null,$sort="id")
		{
			$this->type = $type;
			$this->tableName = $type."Statuses";
			$this->select = "select s.id from {$this->tableName} s";
			$this->sort = $sort;
			if (is_array($fields)) $this->find($fields);
		}


		public function find($fields=null,$sort="id",$limit=null,$groupBy=null)
		{
			$this->sort = $sort;
			$this->limit = $limit;
			$this->groupBy = $groupBy;

			$options = array();
			if (isset($fields['id'])) { $options[] = "id='$fields[id]'"; }
			if (isset($fields['status'])) { $options[] = "status='$fields[status]'"; }


			$this->populateList($options);
		}

		protected function loadResult($key) { return new Status($this->type,$this->list[$key]); }
	}
?>