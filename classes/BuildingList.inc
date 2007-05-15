<?php
/**
 * @copyright Copyright (C) 2006,2007 City of Bloomington, Indiana. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
	class BuildingList extends PDOResultIterator
	{
		public function __construct($fields=null,$sort="id")
		{
			$this->select = "select distinct buildings.id from buildings";
			$this->sort = $sort;
			if (is_array($fields)) $this->find($fields);
		}


		public function find($fields=null,$sort="id",$limit=null,$groupBy=null)
		{
			$this->sort = $sort;
			$this->limit = $limit;
			$this->groupBy = $groupBy;

			$options = array();
			if (isset($fields['id'])) { $options[] = "id='$fields[id]'"; }
			if (isset($fields['name'])) { $options[] = "name='$fields[name]'"; }
			if (isset($fields['GISTag'])) { $options[] = "GISTag='$fields[GISTag]'"; }
			if (isset($fields['buildingStatus_id'])) { $options[] = "buildingStatus_id='$fields[buildingStatus_id]'"; }

			# Dates can come in as arrays as well as strings
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


			# Finding on fields from other tables required joining those tables.
			# You can add fields from other tables to $options by adding the join SQL
			# to $this->joins here
			if (isset($fields['place_id']))
			{
				$this->joins.= " left join building_places on buildings.id=building_id";
				$options[] = "place_id=$fields[place_id]";
			}

			$this->populateList($options);
		}

		public function search(array $fields,$sort="id")
		{
			$this->sort = $sort;
			$options = array();
			if (isset($fields['id'])) { $options[] = "id='$fields[id]'"; }
			if (isset($fields['name'])) { $options[] = "name like '$fields[name]%'"; }
			if (isset($fields['GISTag'])) { $options[] = "GISTag like '$fields[GISTag]%'"; }
			if (isset($fields['buildingStatus_id'])) { $options[] = "buildingStatus_id='$fields[buildingsStatus_id]'"; }

			# Dates can come in as arrays as well as strings
			if (isset($fields['startDate']))
			{
				if (is_array($fields['startDate']))
				{
					# If we've got a full date. search on the date,
					# otherwise, search on the portions they gave us
					if ($fields['startDate']['mon'] && $fields['startDate']['mday'] && $fields['startDate']['year'])
						{ $options[] = "startDate='{$fields['startDate']['year']}-{$fields['startDate']['mon']}-{$fields['startDate']['mday']}'"; }
					else
					{
						# Handle each portion of the date individually
						if ($fields['startDate']['year']) { $options[] = "year(startDate)='{$fields['startDate']['year']}'"; }
						if ($fields['startDate']['mon']) { $options[] = "month(startDate)='{$fields['startDate']['mon']}'"; }
						if ($fields['startDate']['mday']) { $options[] = "dayofmonth(startDate)='{$fields['startDate']['mday']}'"; }
					}
				}
				else { $options[] = "startDate='$fields[startDate]'"; }
			}
			if (isset($fields['endDate']))
			{
				if (is_array($fields['endDate']))
				{
					# If we've got a full date. search on the date,
					# otherwise, search on the portions they gave us
					if ($fields['endDate']['mon'] && $fields['endDate']['mday'] && $fields['endDate']['year'])
						{ $options[] = "endDate='{$fields['endDate']['year']}-{$fields['endDate']['mon']}-{$fields['endDate']['mday']}'"; }
					else
					{
						# Handle each portion of the date individually
						if ($fields['endDate']['year']) { $options[] = "year(endDate)='{$fields['endDate']['year']}'"; }
						if ($fields['endDate']['mon']) { $options[] = "month(endDate)='{$fields['endDate']['mon']}'"; }
						if ($fields['endDate']['mday']) { $options[] = "dayofmonth(endDate)='{$fields['endDate']['mday']}'"; }
					}
				}
				else { $options[] = "endDate='$fields[endDate]'"; }
			}


			# Finding on fields from other tables required joining those tables.
			# You can add fields from other tables to $options by adding the join SQL
			# to $this->joins here
			if (isset($fields['place_id']))
			{
				$this->joins.= " left join building_places on buildings.id=building_id";
				$options[] = "place_id=$fields[place_id]";
			}

			$this->populateList($options);
		}

		protected function loadResult($key) { return new Building($this->list[$key]); }
	}
?>