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
	class UnitList extends PDOResultIterator
	{
		public function __construct($fields=null,$sort="identifier")
		{
			$this->select = "select units.id from units";
			$this->sort = $sort;
			if (is_array($fields)) $this->find($fields);
		}

		public function find($fields=null,$sort="identifier")
		{
			global $PDO;

			$options = array();
			if (isset($fields['id'])) { $options[] = "id=$fields[id]"; }
			if (isset($fields['place_id'])) { $options[] = "place_id=$fields[place_id]"; }
			if (isset($fields['unitType_id'])) { $options[] = "unitType_id=$fields[unitType_id]"; }
			if (isset($fields['building_id'])) { $options[] = $fields['building_id']=="null" ? "building_id is null" : "building_id=$fields[building_id]"; }
			if (isset($fields['identifier'])) { $options[] = "identifier='$fields[identifier]'"; }
			if (isset($fields['mailable'])) { $options[] = "mailable='$fields[mailable]'"; }
			if (isset($fields['livable'])) { $options[] = "livable='$fields[livable]'"; }
			if (isset($fields['notes'])) { $options[] = "notes='$fields[notes]'"; }

			$this->populateList($options);
		}


		protected function loadResult($key) { return new Unit($this->list[$key]); }
	}
?>