<?php
/**
* @copyright Copyright (C) 2006 City of Bloomington, Indiana. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
*/
	class AnnexationList extends PDOResultIterator
	{
		public function __construct($fields=null,$sort="id")
		{
			$this->select = "select annexations.id from annexations";
			$this->sort = $sort;
			if (is_array($fields)) $this->find($fields);
		}

		public function find($fields=null,$sort="id")
		{
			$this->sort = $sort;

			$options = array();
			if (isset($fields['id'])) { $options[] = "id=$fields[id]"; }
			if (isset($fields['ordinanceNumber'])) { $options[] = "ordinanceNumber='$fields[ordinanceNumber]'"; }
			if (isset($fields['name'])) { $options[] = "name='$fields[name]'"; }

			$this->populateList($options);
		}


		protected function loadResult($key) { return new Annexation($this->list[$key]); }
	}
?>