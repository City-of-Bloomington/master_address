<?php
	class PlaceHistory extends PDOResultIterator
	{
		public function __construct(PDO $PDO,$fields=null)
		{
			$this->PDO = $PDO;
			if (is_array($fields)) $this->find($fields);
		}

		public function find($fields=null,$sort="date")
		{
			$options = array();
			if (count($options)) { $this->where = " where ".implode(" and ",$options); }
			$sql = "select * from placeHistory {$this->joins} {$this->where} order by $sort";

			$result = $this->PDO->query($sql);
			if ($result)
			{
				foreach($result as $row) { $this->list[] = $row['id']; }
			}
			else { $e = $this->PDO->errorInfo(); throw new Exception($sql.$e[2]); }
		}


		protected function loadResult($key) { return new Place($this->PDO,$this->list[$key]); }
	}

	class Action
	{
		private $place_id;
		private $action;
		private $date;
		private $notes;
		private $user_id;

		public function __construct(PDO $PDO,$place_id,$action,$date,$notes,$user_id);
	}
?>