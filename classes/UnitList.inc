<?php
/**
 * @copyright Copyright (C) 2006,2007 City of Bloomington, Indiana. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
	class UnitList extends PDOResultIterator
	{
		public function __construct($fields=null,$sort="identifier")
		{
			$this->select = "select units.id from units";
			$this->sort = $sort;
			if (is_array($fields)) $this->find($fields);
		}

		public function find($fields=null,$sort="identifier",$limit=null,$groupBy=null)
		{
			$this->sort = $sort;
			$this->limit = $limit;
			$this->groupBy = $groupBy;

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