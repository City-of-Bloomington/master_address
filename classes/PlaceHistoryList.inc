<?php
/**
* @copyright Copyright (C) 2006 City of Bloomington, Indiana. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
*/
	class PlaceHistoryList extends PDOResultIterator
	{
		public function __construct($fields=null,$sort="id")
		{
			$this->select = "select placeHistory.id from placeHistory";
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
			if (isset($fields['place_id'])) { $options[] = "place_id='$fields[place_id]'"; }
			if (isset($fields['action'])) { $options[] = "action='$fields[action]'"; }
			if (isset($fields['date'])) { $options[] = "date='$fields[date]'"; }
			if (isset($fields['notes'])) { $options[] = "notes='$fields[notes]'"; }
			if (isset($fields['user_id'])) { $options[] = "user_id='$fields[user_id]'"; }


			# Finding on fields from other tables required joining those tables.
			# You can add fields from other tables to $options by adding the join SQL
			# to $this->joins here

			$this->populateList($options);
		}

		/*
		* To find records based on loose matches or fulltext searches,
		* create a search function here
		*/

		protected function loadResult($key) { return new PlaceHistory($this->list[$key]); }
	}
?>