<?php
	require_once(GLOBAL_INCLUDES."/classes/PDOResultIterator.inc");
	require_once(APPLICATION_HOME."/classes/Name.inc");

	class NameList extends PDOResultIterator
	{

		public function find($fields=null,$sort="nameID")
		{
			global $PDO;

			$options = array();
			if (isset($fields['nameID'])) { $options[] = "nameID=$fields[nameID]"; }
			if (isset($fields['townID'])) { $options[] = "townID=$fields[townID]"; }
			if (isset($fields['directionCode'])) { $options[] = "directionCode='$fields[directionCode]'"; }
			if (isset($fields['name'])) { $options[] = "name='$fields[name]'"; }
			if (isset($fields['suffixAbbreviation'])) { $options[] = "suffixAbbreviation='$fields[suffixAbbreviation]'"; }
			if (isset($fields['postDirectionCode'])) { $options[] = "postDirectionCode='$fields[postDirectionCode]'"; }
			if (isset($fields['startDate'])) { $options[] = "startDate='$fields[startDate]'"; }
			if (isset($fields['endDate'])) { $options[] = "endDate='$fields[endDate]'"; }
			if (isset($fields['notes'])) { $options[] = "notes='$fields[notes]'"; }

			if (count($options)) { $where = " where ".implode(" and ",$options); } else { $where = ""; }
			$sql = "select nameID from names $where order by $sort";

			$result = $PDO->query($sql);
			if ($result)
			{
				foreach($result as $row) { $this->list[] = $row['nameID']; }
			}
			else { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
		}


		protected function loadCurrent() { return new Name(current($this->list)); }
	}
?>