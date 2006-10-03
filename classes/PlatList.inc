<?php
/**
* @copyright Copyright (C) 2006 City of Bloomington, Indiana. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
*/
	class PlatList extends PDOResultIterator
	{
		public function __construct($fields=null,$sort="id")
		{
			$this->select = "select plats.id from plats";
			$this->sort = $sort;
			if (is_array($fields)) $this->find($fields);
		}


		/*
		* This will find records based on exact matches to the fields entered
		*/
		public function find($fields=null,$sort="id")
		{
			$this->sort = $sort;

			$options = array();
			if (isset($fields['id'])) { $options[] = "id='$fields[id]'"; }
			if (isset($fields['name'])) { $options[] = "name='$fields[name]'"; }
			if (isset($fields['township_id'])) { $options[] = "township_id='$fields[township_id]'"; }
			if (isset($fields['platType_id'])) { $options[] = "platType_id='$fields[platType_id]'"; }
			if (isset($fields['cabinet'])) { $options[] = "cabinet='$fields[cabinet]'"; }
			if (isset($fields['envelope'])) { $options[] = "envelope='$fields[envelope]'"; }
			if (isset($fields['notes'])) { $options[] = "notes='$fields[notes]'"; }


			# Finding on fields from other tables required joining those tables.
			# You can add fields from other tables to $options by adding the join SQL
			# to $this->joins here

			$this->populateList($options);
		}

		/*
		* To find records based on loose matches or fulltext searches,
		* create a search function here
		*/

		protected function loadResult($key) { return new Plat($this->list[$key]); }
	}
?>