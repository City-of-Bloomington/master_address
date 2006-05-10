<?php
	require_once(GLOBAL_INCLUDES."/classes/PDOResultIterator.inc");
	require_once(APPLICATION_HOME."/classes/Address.inc");

	class AddressList extends PDOResultIterator
	{

		public function find($fields=null,$sort="id")
		{
			global $PDO;

			$options = array();
			if (isset($fields['id'])) { $options[] = "id=$fields[id]"; }
			if (isset($fields['place_id'])) { $options[] = "place_id=$fields[place_id]"; }
			if (isset($fields['segment_id'])) { $options[] = "segment_id=$fields[segment_id]"; }
			if (isset($fields['number'])) { $options[] = "number=$fields[number]"; }
			if (isset($fields['suffix'])) { $options[] = "suffix='$fields[suffix]'"; }
			if (isset($fields['addressType'])) { $options[] = "addressType='$fields[addressType]'"; }
			if (isset($fields['city_id'])) { $options[] = "city_id=$fields[city_id]"; }
			if (isset($fields['zip'])) { $options[] = "zip=$fields[zip]"; }
			if (isset($fields['zipplus4'])) { $options[] = "zipplus4=$fields[zipplus4]"; }
			if (isset($fields['status_id'])) { $options[] = "status_id=$fields[status_id]"; }
			if (isset($fields['active'])) { $options[] = "active='$fields[active]'"; }
			if (isset($fields['censusBlockFIPSCode'])) { $options[] = "censusBlockFIPSCode='$fields[censusBlockFIPSCode]'"; }
			if (isset($fields['statePlaneX'])) { $options[] = "statePlaneX=$fields[statePlaneX]"; }
			if (isset($fields['statePlaneY'])) { $options[] = "statePlaneY=$fields[statePlaneY]"; }
			if (isset($fields['latitude'])) { $options[] = "latitude='$fields[latitude]'"; }
			if (isset($fields['longitude'])) { $options[] = "longitude='$fields[longitude]'"; }
			if (isset($fields['startDate'])) { $options[] = "startDate='$fields[startDate]'"; }
			if (isset($fields['endDate'])) { $options[] = "endDate='$fields[endDate]'"; }
			if (isset($fields['notes'])) { $options[] = "notes='$fields[notes]'"; }

			if (count($options)) { $where = " where ".implode(" and ",$options); } else { $where = ""; }
			$sql = "select id from addresses $where order by $sort";

			$result = $PDO->query($sql);
			if ($result)
			{
				foreach($result as $row) { $this->list[] = $row['id']; }
			}
			else { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
		}


		protected function loadCurrent() { return new Address(current($this->list)); }
	}
?>