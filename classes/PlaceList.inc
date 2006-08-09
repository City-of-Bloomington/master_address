<?php
	class PlaceList extends PDOResultIterator
	{
		public function __construct($fields=null) { if (is_array($fields)) $this->find($fields); }

		public function find($fields=null,$sort="id")
		{
			global $PDO;
			$this->sort = $sort;

			$options = array();
			if (isset($fields['id'])) { $options[] = "places.id=$fields[id]"; }
			if (isset($fields['name'])) { $options[] = "name='$fields[name]'"; }
			if (isset($fields['township_id'])) { $options[] = "township_id=$fields[township_id]"; }
			if (isset($fields['jurisdiction_id'])) { $options[] = "jurisdiction_id=$fields[jurisdiction_id]"; }
			if (isset($fields['trashPickupDay_id'])) { $options[] = "trashPickupDay_id=$fields[trashPickupDay_id]"; }
			if (isset($fields['trashLargeItemPickupDay_id'])) { $options[] = "trashLargeItemPickupDay_id=$fields[trashLargeItemPickupDay_id]"; }
			if (isset($fields['recyclingPickupWeek_id'])) { $options[] = "recyclingPickupWeek_id=$fields[recyclingPickupWeek_id]"; }
			if (isset($fields['mailable'])) { $options[] = "mailable='$fields[mailable]'"; }
			if (isset($fields['livable'])) { $options[] = "livable='$fields[livable]'"; }
			if (isset($fields['section'])) { $options[] = "section='$fields[section]'"; }
			if (isset($fields['quarterSection'])) { $options[] = "quarterSection='$fields[quarterSection]'"; }
			if (isset($fields['class'])) { $options[] = "class='$fields[class]'"; }
			if (isset($fields['startDate'])) { $options[] = "places.startDate='$fields[startDate]'"; }
			if (isset($fields['endDate'])) { $options[] = "places.endDate='$fields[endDate]'"; }
			if (isset($fields['status_id'])) { $options[] = "status_id='$fields[status_id]'"; }
			if (isset($fields['plat_id'])) { $options[] = "plat_id='$fields[plat_id]'"; }
			if (isset($fields['lotNumber'])) { $options[] = "lotNumber='$fields[lotNumber]'"; }


			# To find by district we have to join the district_places table
			if (isset($fields['district_id']))
			{
				$this->joins.= " left join district_places d on d.place_id=places.id";
				$options[] = "district_id=$fields[district_id]";
			}

			# To find by segment_id join the addresses table
			if (isset($fields['segment_id']))
			{
				$this->joins.= " left join addresses on addresses.place_id=places.id";
				$options[] = "segment_id=$fields[segment_id]";
			}

			if (count($options)) { $this->where = " where ".implode(" and ",$options); }
			$sql = "select distinct places.id from places {$this->joins} {$this->where} order by {$this->sort}";

			$result = $PDO->query($sql);
			if ($result)
			{
				foreach($result as $row) { $this->list[] = $row['id']; }
			}
			else { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
		}


		protected function loadResult($key) { return new Place($this->list[$key]); }
	}
?>