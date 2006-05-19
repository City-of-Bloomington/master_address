<?php
	require_once(GLOBAL_INCLUDES."/classes/PDOResultIterator.inc");
	require_once(APPLICATION_HOME."/classes/Street.inc");

	class StreetList extends PDOResultIterator
	{
		private $joins = "";
		private $where = "";
		private $sort;

		public function find($fields=null,$sort="id")
		{
			global $PDO;

			$this->sort = $sort;

			$options = array();
			if (isset($fields['id'])) { $options[] = "id=$fields[id]"; }
			if (isset($fields['notes'])) { $options[] = "notes='$fields[notes]'"; }
			if (isset($fields['status_id'])) { $options[] = "status_id=$fields[status_id]"; }

			# Find by Segment requires us to join the street_segments table
			if (isset($fields['segment_id']))
			{
				$this->joins = "left join street_segments on id=street_id";
				$options[] = "segment_id=$fields[segment_id]";
			}

			if (count($options)) { $this->where = "where ".implode(" and ",$options); }

			$sql = "select id from streets {$this->joins} {$this->where} order by {$this->sort}";
			$result = $PDO->query($sql);
			if ($result)
			{
				foreach($result as $row) { $this->list[] = $row['id']; }
			}
			else { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
		}


		protected function loadResult($key) { return new Street($this->list[$key]); }
	}
?>