<?php
/**
* @copyright Copyright (C) 2006 City of Bloomington, Indiana. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
* This file is part of the City of Bloomington's Master Address system.
* Master Address is free software; you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation; either version 2 of the License, or
* (at your option) any later version.
*
* Master Address is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with Master Address; if not, write to the Free Software
* Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA
*/
	class StreetList extends PDOResultIterator
	{
		public function __construct($fields=null,$sort="id")
		{
			$this->select = "select streets.id from streets";
			$this->sort = $sort;
			if (is_array($fields)) $this->find($fields);
		}

		public function find($fields=null,$sort="id")
		{
			$this->sort = $sort;

			$options = array();
			if (isset($fields['id'])) { $options[] = "streets.id=$fields[id]"; }
			if (isset($fields['notes'])) { $options[] = "notes='$fields[notes]'"; }
			if (isset($fields['status_id'])) { $options[] = "status_id=$fields[status_id]"; }

			# Find by Segment requires us to join the street_segments table
			if (isset($fields['segment_id']))
			{
				$this->joins.= " left join street_segments on streets.id=street_id";
				$options[] = "segment_id=$fields[segment_id]";
			}

			# Finding on street name informatino requires joining streetNames and names
			if (isset($fields['direction_id']) || isset($fields['name']) || isset($fields['suffix_id']) || isset($fields['postDirection_id']))
			{
				$this->joins.= " left join streetNames on streets.id=streetNames.street_id left join names on streetNames.name_id=names.id";

				if (isset($fields['direction_id'])) { $options[] = "direction_id=$fields[direction_id]"; }
				if (isset($fields['suffix_id'])) { $options[] = "suffix_id=$fields[suffix_id]"; }
				if (isset($fields['postDirection_id'])) { $options[] = "postDirection_id=$fields[postDirection_id]"; }
				if (isset($fields['name'])) { $options[] = "name='$fields[name]'"; }
			}

			$this->populateList($options);
		}

		public function search(array $fields,$sort="id")
		{
			$this->sort = $sort;

			$options = array();
			if (isset($fields['id'])) { $options[] = "streets.id=$fields[id]"; }
			if (isset($fields['notes'])) { $options[] = "notes='$fields[notes]'"; }
			if (isset($fields['status_id'])) { $options[] = "status_id=$fields[status_id]"; }

			# Find by Segment requires us to join the street_segments table
			if (isset($fields['segment_id']))
			{
				$this->joins.= " left join street_segments on streets.id=street_id";
				$options[] = "segment_id=$fields[segment_id]";
			}

			# Finding on street name informatino requires joining streetNames and names
			if (isset($fields['direction_id']) || isset($fields['name']) || isset($fields['suffix_id']) || isset($fields['postDirection_id']))
			{
				$this->joins.= " left join streetNames on streets.id=streetNames.street_id left join names on streetNames.name_id=names.id";

				if (isset($fields['direction_id'])) { $options[] = "direction_id=$fields[direction_id]"; }
				if (isset($fields['suffix_id'])) { $options[] = "suffix_id=$fields[suffix_id]"; }
				if (isset($fields['postDirection_id'])) { $options[] = "postDirection_id=$fields[postDirection_id]"; }
				if (isset($fields['name'])) { $options[] = "name like '$fields[name]%'"; }
			}

			$this->populateList($options);
		}



		protected function loadResult($key) { return new Street($this->list[$key]); }
	}
?>