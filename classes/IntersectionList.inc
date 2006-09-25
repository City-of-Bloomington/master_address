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
	class IntersectionList extends PDOResultIterator
	{
		public function __construct($fields=null,$sort="id")
		{
			$this->select = "select intersections.id from intersections";
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
			if (isset($fields['tag'])) { $options[] = "tag='$fields[tag]'"; }
			if (isset($fields['name'])) { $options[] = "name='$fields[name]'"; }
			if (isset($fields['intersectionStatus_id'])) { $options[] = "intersectionStatus_id='$fields[intersectionStatus_id]'"; }
			if (isset($fields['jurisdiction_id'])) { $options[] = "jurisdiction_id='$fields[jurisdiction_id]'"; }
			if (isset($fields['x'])) { $options[] = "x='$fields[x]'"; }
			if (isset($fields['y'])) { $options[] = "y='$fields[y]'"; }
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

		protected function loadResult($key) { return new Intersection($this->list[$key]); }
	}
?>