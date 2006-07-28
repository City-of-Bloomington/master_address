<?php
	class BuildingList extends PDOResultIterator
	{
		public function __construct($fields=null) { if (is_array($fields)) $this->find($fields); }


		public function find($fields=null,$sort="id")
		{
			global $PDO;
			$this->sort = $sort;

			$options = array();
			if (isset($fields['id'])) { $options[] = "id='$fields[id]'"; }
			if (isset($fields['name'])) { $options[] = "name='$fields[name]'"; }
			if (isset($fields['GISTag'])) { $options[] = "GISTag='$fields[GISTag]'"; }
			if (isset($fields['status_id'])) { $options[] = "status_id='$fields[status_id]'"; }

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


			if (count($options)) { $this->where = "where ".implode(" and ",$options); }
			$sql = "select distinct buildings.id from buildings {$this->joins} {$this->where} order by {$this->sort}";

			$result = $PDO->query($sql);
			if ($result)
			{
				foreach($result as $row) { $this->list[] = $row['id']; }
			}
			else { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
		}


		protected function loadResult($key) { return new Building($this->list[$key]); }
	}
?>