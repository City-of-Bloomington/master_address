<?php
/**
* @copyright Copyright (C) 2006 City of Bloomington, Indiana. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
*/
	class PlaceHistory extends ActiveRecord
	{
		private $id;
		private $place_id;
		private $action;
		private $date;
		private $notes;
		private $user_id;

		private $place;
		private $user;

		public function __construct($id=null)
		{
			global $PDO;

			if ($id)
			{
				$sql = "select * from placeHistory where id=?";
				try
				{
					$query = $PDO->prepare($sql);
					$query->execute(array($id));
				}
				catch (Exception $e) { throw $e; }

				$result = $query->fetchAll();
				foreach($result[0] as $field=>$value) { if ($value) $this->$field = $value; }
			}
			else
			{
				# This is where the code goes to generate a new, empty instance.
				# Set any default values for properties that need it here
				$this->user_id = $_SESSION['USER']->getId();
				$this->date = $this->dateArrayToString(getdate());
			}
		}


		/**
		 * This generates generic SQL that should work right away.
		 * You can replace this $fields code with your own custom SQL
		 * for each property of this class,
		 */
		public function save()
		{
			# Check for required fields here.  Throw an exception if anything is missing.
			if (!$this->place_id || !$this->action) { throw new Exception("missingRequiredFields"); }


			$fields = array();
			$fields['place_id'] = $this->place_id ? $this->place_id : null;
			$fields['action'] = $this->action ? $this->action : null;
			$fields['date'] = $this->date ? $this->date : null;
			$fields['notes'] = $this->notes ? $this->notes : null;
			$fields['user_id'] = $this->user_id ? $this->user_id : null;

			# Split the fields up into a preparedFields array and a values array.
			# PDO->execute cannot take an associative array for values, so we have
			# to strip out the keys from $fields
			$preparedFields = array();
			foreach($fields as $key=>$value)
			{
				$preparedFields[] = "$key=?";
				$values[] = $value;
			}
			$preparedFields = implode(",",$preparedFields);


			if ($this->id) { $this->update($values,$preparedFields); }
			else { $this->insert($values,$preparedFields); }
		}

		private function update($values,$preparedFields)
		{
			global $PDO;

			$sql = "update placeHistory set $preparedFields where id={$this->id}";
			if (false === $query = $PDO->prepare($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
			if (false === $query->execute($values)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
		}

		private function insert($values,$preparedFields)
		{
			global $PDO;

			$sql = "insert placeHistory set $preparedFields";
			if (false === $query = $PDO->prepare($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
			if (false === $query->execute($values)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
			$this->id = $PDO->lastInsertID();
		}


		public function getId() { return $this->id; }
		public function getPlace_id() { return $this->place_id; }
		public function getAction() { return $this->action; }
		public function getDate() { return $this->date; }
		public function getNotes() { return $this->notes; }
		public function getUser_id() { return $this->user_id; }
		public function getPlace()
		{
			if ($this->place_id)
			{
				if (!$this->place) { $this->place = new Place($this->place_id); }
				return $this->place;
			}
			else return null;
		}
		public function getUser()
		{
			if (!$this->user) { $this->user = new User($this->user_id); }
			return $this->user;
		}


		public function setPlace_id($int) { $this->place_id = ereg_replace("[^0-9]","",$int); }
		public function setAction($string) { $this->action = trim($string); }
		public function setDate($date) { $this->date = is_array($date) ? $this->dateArrayToString($date) : $date; }
		public function setNotes($string) { $this->notes = trim($string); }
		public function setUser_id($int) { $this->user_id = ereg_replace("[^0-9]","",$int); }
		public function setPlace($place) { $this->place_id = $place->getId(); $this->place = $place; }
		public function setUser($user) { $this->user_id = $user->getId(); $this->user = $user; }
	}
?>