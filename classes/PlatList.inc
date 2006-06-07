<?php
	class PlatList extends PDOResultIterator
	{
		public function __construct($fields=null) { if (is_array($fields)) $this->find($fields); }

		public function find($fields=null,$sort="id")
		{
			global $PDO;

			$options = array();
			if (isset($fields['id'])) { $options[] = "id=$fields[id]"; }
			if (isset($fields['name'])) { $options[] = "name='$fields[name]'"; }
			if (isset($fields['township_id'])) { $options[] = "township_id=$fields[township_id]"; }
			if (isset($fields['platType_id'])) { $options[] = "platType_id=$fields[platType_id]"; }
			if (isset($fields['cabinet'])) { $options[] = "cabinet='$fields[cabinet]'"; }
			if (isset($fields['envelope'])) { $options[] = "envelope='$fields[envelope]'"; }
			if (isset($fields['notes'])) { $options[] = "notes='$fields[notes]'"; }

			if (count($options)) { $where = " where ".implode(" and ",$options); } else { $where = ""; }
			$sql = "select id from plats $where order by $sort";

			$result = $PDO->query($sql);
			if ($result)
			{
				foreach($result as $row) { $this->list[] = $row['id']; }
			}
			else { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
		}


		protected function loadResult($key) { return new Plat($this->list[$key]); }
	}
?>