<?php
	require_once(GLOBAL_INCLUDES."/classes/PDOResultIterator.inc");
	require_once(APPLICATION_HOME."/classes/PlatType.inc");

	class PlatTypeList extends PDOResultIterator
	{

		public function find($fields=null,$sort="type")
		{
			global $PDO;

			$options = array();
			if (isset($fields['type'])) { $options[] = "type='$fields[type]'"; }
			if (isset($fields['description'])) { $options[] = "description='$fields[description]'"; }

			if (count($options)) { $where = " where ".implode(" and ",$options); } else { $where = ""; }
			$sql = "select type from platTypes $where order by $sort";

			$result = $PDO->query($sql);
			if ($result)
			{
				foreach($result as $row) { $this->list[] = $row['type']; }
			}
			else { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
		}


		protected function loadCurrent() { return new PlatType(current($this->list)); }
	}
?>