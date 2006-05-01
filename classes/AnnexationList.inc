<?php
	require_once(GLOBAL_INCLUDES."/classes/PDOResultIterator.inc");
	require_once(APPLICATION_HOME."/classes/Annexation.inc");

	class AnnexationList extends PDOResultIterator
	{

		public function find($fields=null,$sort="ordinanceNumber")
		{
			global $PDO;

			$options = array();
			if (isset($fields['ordinanceNumber'])) { $options[] = "ordinanceNumber='$fields[ordinanceNumber]'"; }
			if (isset($fields['name'])) { $options[] = "name='$fields[name]'"; }

			if (count($options)) { $where = " where ".implode(" and ",$options); } else { $where = ""; }
			$sql = "select ordinanceNumber from annexations $where order by $sort";

			$result = $PDO->query($sql);
			if ($result)
			{
				foreach($result as $row) { $this->list[] = $row['ordinanceNumber']; }
			}
			else { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
		}


		protected function loadCurrent() { return new Annexation(current($this->list)); }
	}
?>