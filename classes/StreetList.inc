<?php
	class StreetList extends PDOResultIterator
	{
		private $joins = "";
		private $where = "";
		private $sort;

		public function __construct($fields=null) { if (is_array($fields)) $this->find($fields); }

		public function find($fields=null,$sort="id")
		{
			global $PDO;

			$this->sort = $sort;

			$options = array();
			if (isset($fields['id'])) { $options[] = "streets.id=$fields[id]"; }
			if (isset($fields['notes'])) { $options[] = "notes='$fields[notes]'"; }
			if (isset($fields['status_id'])) { $options[] = "status_id=$fields[status_id]"; }

			# Find by Segment requires us to join the street_segments table
			if (isset($fields['segment_id']))
			{
				$this->joins.= " left join street_segments on streets.id=street_id";
				$options[] = "segment_id=$fields[segment_id]";
			}

			# Finding on street name informatino requires joining streetNames and names
			if (isset($fields['direction_id']) || isset($fields['name']) || isset($fields['suffix_id']) || isset($fields['postDirection_id']))
			{
				$this->joins.= " left join streetNames on streets.id=streetNames.street_id left join names on streetNames.name_id=names.id";

				if (isset($fields['direction_id'])) { $options[] = "direction_id=$fields[direction_id]"; }
				if (isset($fields['suffix_id'])) { $options[] = "suffix_id=$fields[suffix_id]"; }
				if (isset($fields['postDirection_id'])) { $options[] = "postDirection_id=$fields[postDirection_id]"; }
				if (isset($fields['name'])) { $options[] = "name='$fields[name]'"; }
			}


			if (count($options)) { $this->where = "where ".implode(" and ",$options); }

			$sql = "select streets.id from streets {$this->joins} {$this->where} order by {$this->sort}";
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