<?php
	require_once(GLOBAL_INCLUDES."/classes/PDOResultIterator.inc");
	require_once(APPLICATION_HOME."/classes/StatusCode.inc");

	class StatusCodeList extends PDOResultIterator
	{

		public function find($fields=null,$sort="statusCode")
		{
			global $PDO;

			$options = array();
			if (isset($fields['statusCode'])) { $options[] = "statusCode='$fields[statusCode]'"; }
			if (isset($fields['status'])) { $options[] = "status='$fields[status]'"; }

			if (count($options)) { $where = " where ".implode(" and ",$options); } else { $where = ""; }
			$sql = "select statusCode from statusCodes $where order by $sort";

			foreach($PDO->query($sql) as $row) { $this->list[] = $row['statusCode']; }
		}


		protected function loadCurrent() { return new StatusCode(current($this->list)); }
	}
?>