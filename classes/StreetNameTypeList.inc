<?php
	class StreetNameTypeList extends PDOResultIterator
	{
		public function __construct($fields=null) { if (is_array($fields)) $this->find($fields); }


		public function find($fields=null,$sort="id")
		{
			global $PDO;
			$this->sort = $sort;

			$options = array();
			if (isset($fields['id'])) { $options[] = "id='$fields[id]'"; }
			if (isset($fields['type'])) { $options[] = "type='$fields[type]'"; }
			if (isset($fields['description'])) { $options[] = "description='$fields[description]'"; }


			# Finding on fields from other tables required joining those tables.
			# You can add fields from other tables to $options by adding the join SQL
			# to $this->joins here


			if (count($options)) { $this->where = "where ".implode(" and ",$options); }
			$sql = "select id from streetNameTypes {$this->joins} {$this->where} order by {$this->sort}";

			$result = $PDO->query($sql);
			if ($result)
			{
				foreach($result as $row) { $this->list[] = $row['id']; }
			}
			else { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
		}


		protected function loadResult($key) { return new StreetNameType($this->list[$key]); }
	}
?>