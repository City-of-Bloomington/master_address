<?php
	class BuildingList extends PDOResultIterator
	{
		public function __construct($fields=null) { if (is_array($fields)) $this->find($fields); }

		public function find($fields=null,$sort="id")
		{
			global $PDO;

			$options = array();
			if (isset($fields['id'])) { $options[] = "id=$fields[id]"; }
			if (isset($fields['place_id'])) { $options[] = "place_id=$fields[place_id]"; }
			if (isset($fields['name'])) { $options[] = "name='$fields[name]'"; }
			if (isset($fields['startDate'])) { $options[] = "startDate='$fields[startDate]'"; }
			if (isset($fields['endDate'])) { $options[] = "endDate='$fields[endDate]'"; }
			if (isset($fields['GISTag'])) { $options[] = "GISTag='$fields[GISTag]'"; }
			if (isset($fields['status_id'])) { $options[] = "status_id=$fields[status_id]"; }

			if (count($options)) { $where = " where ".implode(" and ",$options); } else { $where = ""; }
			$sql = "select id from buildings $where order by $sort";

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