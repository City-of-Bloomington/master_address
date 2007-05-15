<?php
/**
 * @copyright Copyright (C) 2006,2007 City of Bloomington, Indiana. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
	class AddressList extends PDOResultIterator
	{
		public function __construct($fields=null,$sort="a.id")
		{
			$this->select = "select a.id from addresses a";
			$this->sort = $sort;
			if (is_array($fields)) $this->find($fields);
		}

		public function find($fields=null,$sort="a.id",$limit=null,$groupBy=null)
		{
			$this->sort = $sort;
			$this->limit = $limit;
			$this->groupBy = $groupBy;

			$options = array();
			if (isset($fields['id'])) { $options[] = "a.id='$fields[id]'"; }
			if (isset($fields['place_id'])) { $options[] = "a.place_id='$fields[place_id]'"; }
			if (isset($fields['segment_id'])) { $options[] = "a.segment_id='$fields[segment_id]'"; }
			if (isset($fields['number'])) { $options[] = "a.number='$fields[number]'"; }
			if (isset($fields['suffix'])) { $options[] = "a.suffix='$fields[suffix]'"; }
			if (isset($fields['addressType'])) { $options[] = "a.addressType='$fields[addressType]'"; }
			if (isset($fields['city_id'])) { $options[] = "a.city_id='$fields[city_id]'"; }
			if (isset($fields['zip'])) { $options[] = "a.zip='$fields[zip]'"; }
			if (isset($fields['zipplus4'])) { $options[] = "a.zipplus4='$fields[zipplus4]'"; }
			if (isset($fields['addressStatus_id'])) { $options[] = "a.addressStatus_id='$fields[addressStatus_id]'"; }
			if (isset($fields['active'])) { $options[] = "a.active='$fields[active]'"; }
			if (isset($fields['censusBlockFIPSCode'])) { $options[] = "a.censusBlockFIPSCode='$fields[censusBlockFIPSCode]'"; }
			if (isset($fields['statePlaneX'])) { $options[] = "a.statePlaneX='$fields[statePlaneX]'"; }
			if (isset($fields['statePlaneY'])) { $options[] = "a.statePlaneY='$fields[statePlaneY]'"; }
			if (isset($fields['latitude'])) { $options[] = "a.latitude='$fields[latitude]'"; }
			if (isset($fields['longitude'])) { $options[] = "a.longitude='$fields[longitude]'"; }
			if (isset($fields['notes'])) { $options[] = "a.notes='$fields[notes]'"; }
			if (isset($fields['startDate']))
			{
				if (is_array($fields['startDate'])) { $fields['startDate'] = $this->dateArrayToString($fields['startDate']); }
				if ($fields['startDate']) { $options[] = "a.startDate='$fields[startDate]'"; }
			}
			if (isset($fields['endDate']))
			{
				if (is_array($fields['endDate'])) { $fields['endDate'] = $this->dateArrayToString($fields['endDate']); }
				if ($fields['endDate']) { $options[] = "a.endDate='$fields[endDate]'"; }
			}

			# Searching on street name information requires some joins
			if (isset($fields['direction_id']) || isset($fields['name']) || isset($fields['suffix_id']) || isset($fields['postDirection_id']))
			{
				$this->joins.= "left join streets on a.street_id=streets.id
								left join streetNames on streets.id=streetNames.street_id
								left join names on streetNames.name_id=names.id";
				if (isset($fields['direction_id'])) { $options[] = "direction_id='$fields[direction_id]'"; }
				if (isset($fields['name'])) { $options[] = "name='$fields[name]'"; }
				if (isset($fields['suffix_id'])) { $options[] = "suffix_id='$fields[suffix_id]'"; }
				if (isset($fields['postDirection_id'])) { $options[] = "postDirection_id='$fields[postDirection_id]'"; }
			}

			$this->populateList($options);
		}


		protected function loadResult($key) { return new Address($this->list[$key]); }
	}
?>