<?php
	class AddressSearch extends PDOResultIterator
	{
		public function __construct($fields=null) { if (is_array($fields)) $this->find($fields); }

		public function find($fields=null,$sort=null)
		{
			global $PDO;

			$options = array();
			if (isset($fields['fullAddress'])) { $options[] = "match(fullAddress) against ('$fields[fullAddress]')"; }
			if (isset($fields['address_id'])) { $options[] = "address_id='$fields[address_id]'"; }
			if (isset($fields['segment_id'])) { $options[] = "segment_id='$fields[segment_id]'"; }
			if (isset($fields['street_id'])) { $options[] = "street_id='$fields[street_id]'"; }
			if (isset($fields['name_id'])) { $options[] = "name_id='$fields[name_id]'"; }
			if (isset($fields['place_id'])) { $options[] = "place_id='$fields[place_id]'"; }
			if (isset($fields['unit_id'])) { $options[] = "unit_id='$fields[unit_id]'"; }

			if (count($options)) { $where = " where ".implode(" and ",$options); } else { $where = ""; }
			$sql = "select * from addressIndex $where";

			$result = $PDO->query($sql);
			if ($result)
			{
				foreach($result as $row) { $this->list[] = $row; }
			}
			else { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
		}


		protected function loadResult($key)
		{
			$row = $this->list[$key];
			return new AddressSearchResult($row['fullAddress'],$row['address_id'],$row['segment_id'],$row['street_id'],$row['name_id'],$row['place_id'],$row['unit_id']);
		}
	}
?>