<?php
	require_once(GLOBAL_INCLUDES."/classes/PDOResultIterator.inc");
	require_once(APPLICATION_HOME."/classes/Suffix.inc");

	class SuffixList extends PDOResultIterator
	{

		public function find($fields=null,$sort="suffix")
		{
			global $PDO;

			$options = array();
			if (isset($fields['suffix'])) { $options[] = "suffix='$fields[suffix]'"; }
			if (isset($fields['description'])) { $options[] = "description='$fields[description]'"; }

			if (count($options)) { $where = " where ".implode(" and ",$options); } else { $where = ""; }
			$sql = "select suffix from suffixes $where order by $sort";

			$result = $PDO->query($sql);
			if ($result)
			{
				foreach($result as $row) { $this->list[] = $row['suffix']; }
			}
			else { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
		}


		protected function loadCurrent() { return new Suffix(current($this->list)); }
	}
?>