<?php
	class TownshipList extends PDOResultIterator
	{
		public function __construct($fields=null) { if (is_array($fields)) $this->find($fields); }

		public function find($fields=null,$sort="id")
		{
			global $PDO;

			$options = array();
			if (isset($fields['id'])) { $options[] = "id=$fields[id]"; }
			if (isset($fields['name'])) { $options[] = "name='$fields[name]'"; }
			if (isset($fields['abbreviation'])) { $options[] = "abbreviation='$fields[abbreviation]'"; }
			if (isset($fields['quarterCode'])) { $options[] = "quarterCode='$fields[quarterCode]'"; }

			if (count($options)) { $where = " where ".implode(" and ",$options); } else { $where = ""; }
			$sql = "select id from townships $where order by $sort";

			$result = $PDO->query($sql);
			if ($result)
			{
				foreach($result as $row) { $this->list[] = $row['id']; }
			}
			else { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
		}


		protected function loadResult($key) { return new Township($this->list[$key]); }
	}
?>