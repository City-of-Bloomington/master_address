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
	class NameList extends PDOResultIterator
	{
		public function __construct($fields=null,$sort="name")
		{
			$this->select = "select names.id from names";
			$this->sort = $sort;
			if (is_array($fields)) $this->find($fields);
		}

		public function find($fields=null,$sort="name")
		{
			$this->sort = $sort;

			$options = array();
			if (isset($fields['id'])) { $options[] = "id=$fields[id]"; }
			if (isset($fields['town_id'])) { $options[] = "town_id=$fields[town_id]"; }
			if (isset($fields['direction_id'])) { $options[] = "direction_id=$fields[direction_id]"; }
			if (isset($fields['name'])) { $options[] = "name='$fields[name]'"; }
			if (isset($fields['suffix_id'])) { $options[] = "suffix_id=$fields[suffix_id]"; }
			if (isset($fields['postDirection_id'])) { $options[] = "postDirection_id=$fields[postDirection_id]"; }
			if (isset($fields['startDate'])) { $options[] = "startDate='$fields[startDate]'"; }
			if (isset($fields['endDate'])) { $options[] = "endDate='$fields[endDate]'"; }
			if (isset($fields['notes'])) { $options[] = "notes='$fields[notes]'"; }

			if (isset($fields['street_id']))
			{
				$this->joins = "left join street_names on id=name_id";
				$options[] = "street_id=$fields[street_id]";
			}

			$this->populateList($options);
		}

		public function search(array $fields,$sort="name")
		{
			$this->sort = $sort;

			$options = array();
			if (isset($fields['id'])) { $options[] = "id=$fields[id]"; }
			if (isset($fields['town_id'])) { $options[] = "town_id=$fields[town_id]"; }
			if (isset($fields['direction_id'])) { $options[] = "direction_id=$fields[direction_id]"; }
			if (isset($fields['name'])) { $options[] = "name like '$fields[name]%'"; }
			if (isset($fields['suffix_id'])) { $options[] = "suffix_id=$fields[suffix_id]"; }
			if (isset($fields['postDirection_id'])) { $options[] = "postDirection_id=$fields[postDirection_id]"; }
			if (isset($fields['startDate'])) { $options[] = "startDate='$fields[startDate]'"; }
			if (isset($fields['endDate'])) { $options[] = "endDate='$fields[endDate]'"; }
			if (isset($fields['notes'])) { $options[] = "notes='$fields[notes]'"; }

			if (isset($fields['street_id']))
			{
				$this->joins = "left join street_names on id=name_id";
				$options[] = "street_id=$fields[street_id]";
			}

			$this->populateList($options);
		}


		protected function loadResult($key) { return new Name($this->list[$key]); }
	}
?>