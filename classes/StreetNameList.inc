<?php
/**
 * @copyright Copyright (C) 2006,2007 City of Bloomington, Indiana. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
	class StreetNameList extends PDOResultIterator
	{
		public function __construct($fields=null,$sort="id")
		{
			$this->select = "select streetNames.id from streetNames";
			$this->sort = $sort;
			if (is_array($fields)) $this->find($fields);
		}


		/*
		* This will find records based on exact matches to the fields entered
		*/
		public function find($fields=null,$sort="id",$limit=null,$groupBy=null)
		{
			$this->sort = $sort;
			$this->limit = $limit;
			$this->groupBy = $groupBy;

			$options = array();
			if (isset($fields['id'])) { $options[] = "id='$fields[id]'"; }
			if (isset($fields['street_id'])) { $options[] = "street_id='$fields[street_id]'"; }
			if (isset($fields['name_id'])) { $options[] = "name_id='$fields[name_id]'"; }
			if (isset($fields['streetNameType_id'])) { $options[] = "streetNameType_id='$fields[streetNameType_id]'"; }

			# Finding on street name information requires joining streetNames and names
			if (isset($fields['direction_id']) || isset($fields['name']) || isset($fields['suffix_id']) || isset($fields['postDirection_id']))
			{
				$this->joins.= "left join names on streetNames.name_id=names.id";
				if ($this->sort == "id") { $this->sort = "name"; }

				if (isset($fields['direction_id'])) { $options[] = "direction_id=$fields[direction_id]"; }
				if (isset($fields['suffix_id'])) { $options[] = "suffix_id=$fields[suffix_id]"; }
				if (isset($fields['postDirection_id'])) { $options[] = "postDirection_id=$fields[postDirection_id]"; }
				if (isset($fields['name'])) { $options[] = "name='$fields[name]'"; }
			}

			$this->populateList($options);
		}

		/*
		* To find records based on loose matches or fulltext searches,
		* create a search function here
		*/
		public function search($fields=null,$sort="id",$limit=null,$groupBy=null)
		{
			$this->sort = $sort;
			$this->limit = $limit;
			$this->groupBy = $groupBy;

			$options = array();
			if (isset($fields['id'])) { $options[] = "id='$fields[id]'"; }
			if (isset($fields['street_id'])) { $options[] = "street_id='$fields[street_id]'"; }
			if (isset($fields['name_id'])) { $options[] = "name_id='$fields[name_id]'"; }
			if (isset($fields['streetNameType_id'])) { $options[] = "streetNameType_id='$fields[streetNameType_id]'"; }

			# Finding on street name information requires joining streetNames and names
			if (isset($fields['direction_id']) || isset($fields['name']) || isset($fields['suffix_id']) || isset($fields['postDirection_id']))
			{
				$this->joins.= "left join names on streetNames.name_id=names.id";
				if ($this->sort == "id") { $this->sort = "name"; }

				if (isset($fields['direction_id'])) { $options[] = "direction_id=$fields[direction_id]"; }
				if (isset($fields['suffix_id'])) { $options[] = "suffix_id=$fields[suffix_id]"; }
				if (isset($fields['postDirection_id'])) { $options[] = "postDirection_id=$fields[postDirection_id]"; }
				if (isset($fields['name'])) { $options[] = "name like '%$fields[name]%'"; }
			}

			$this->populateList($options);
		}

		protected function loadResult($key) { return new StreetName($this->list[$key]); }
	}
?>