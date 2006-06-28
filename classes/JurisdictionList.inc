<?php
	class JurisdictionList extends PDOResultIterator
	{
		public function __construct(PDO $PDO,$fields=null)
		{
			$this->PDO = $PDO;
			if (is_array($fields)) $this->find($fields);
		}

		public function find($fields=null,$sort="id")
		{
			$options = array();
			if (isset($fields['id'])) { $options[] = "id=$fields[id]"; }
			if (isset($fields['name'])) { $options[] = "name='$fields[name]'"; }

			if (count($options)) { $where = " where ".implode(" and ",$options); } else { $where = ""; }
			$sql = "select id from jurisdictions $where order by $sort";

			$result = $this->PDO->query($sql);
			if ($result)
			{
				foreach($result as $row) { $this->list[] = $row['id']; }
			}
			else { $e = $this->PDO->errorInfo(); throw new Exception($sql.$e[2]); }
		}


		protected function loadResult($key) { return new Jurisdiction($this->PDO,$this->list[$key]); }
	}
?>