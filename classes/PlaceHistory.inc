<?php
	class PlaceHistory extends PDOResultIterator
	{
		public function __construct($fields=null) { if (is_array($fields)) $this->find($fields); }

		public function find($fields=null,$sort="date")
		{
			global $PDO;
			$this->sort = $sort;

			$options = array();
			if (count($options)) { $this->where = " where ".implode(" and ",$options); }
			$sql = "select * from placeHistory {$this->joins} {$this->where} order by {$this->sort}";

			$result = $PDO->query($sql);
			if ($result)
			{
				foreach($result as $row) { $this->list[] = $row['id']; }
			}
			else { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
		}


		protected function loadResult($key) { return new Place($this->list[$key]); }
	}

	class Action
	{
		private $place_id;
		private $action;
		private $date;
		private $notes;
		private $user_id;

		public function __construct($place_id,$action,$date,$notes,$user_id);
	}
?>