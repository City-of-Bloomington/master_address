<?php
/**
* @copyright Copyright (C) 2006 City of Bloomington, Indiana. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
* This file is part of the City of Bloomington's Master Address System.
* Master Address is free software; you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation; either version 2 of the License, or
* (at your option) any later version.
*
* This Framework is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with Foobar; if not, write to the Free Software
* Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA
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
				$sql = "select * from placeHistory where id=$id";
				$result = $PDO->query($sql);
				if ($result)
				{
					if ($row = $result->fetch())
					{
						# This will load all fields in the table as properties of this class.
						# You may want to replace this with, or add your own extra, custom loading
						foreach($row as $field=>$value) { if ($value) $this->$field = $value; }

						$result->closeCursor();
					}
					else { throw new Exception($sql); }
				}
				else { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
			}
			else
			{
				# This is where the code goes to generate a new, empty instance.
				# Set any default values for properties that need it here
				$this->user_id = $_SESSION['USER']->getId();
				$this->date = $this->dateArrayToString(getdate());
			}
		}


		public function save()
		{
			# Check for required fields here.  Throw an exception if anything is missing.
			if (!$this->place_id || !$this->action) { throw new Exception("missingRequiredFields"); }


			# This generates generic SQL that should work right away.
			# You can (and maybe should) replace this $fields code with your own custom SQL
			# for each property of this class,
			$fields = array();
			$fields[] = $this->place_id ? "place_id='{$this->place_id}'" : "place_id=null";
			$fields[] = $this->action ? "action='{$this->action}'" : "action=null";
			$fields[] = $this->date ? "date='{$this->date}'" : "date=null";
			$fields[] = $this->notes ? "notes='{$this->notes}'" : "notes=null";
			$fields[] = $this->user_id ? "user_id='{$this->user_id}'" : "user_id=null";

			$fields = implode(",",$fields);


			if ($this->id) { $this->update($fields); }
			else { $this->insert($fields); }
		}

		private function update($fields)
		{
			global $PDO;

			$sql = "update placeHistory set $fields where id={$this->id}";
			if (false === $PDO->exec($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
		}

		private function insert($fields)
		{
			global $PDO;

			$sql = "insert placeHistory set $fields";
			if (false === $PDO->exec($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
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
		public function setAction($string) { $this->action = $this->sanitizeString($string); }
		public function setDate($date) { $this->date = is_array($date) ? $this->dateArrayToString($date) : $date; }
		public function setNotes($string) { $this->notes = $this->sanitizeString($string); }
		public function setUser_id($int) { $this->user_id = ereg_replace("[^0-9]","",$int); }
		public function setPlace($place) { $this->place_id = $place->getId(); $this->place = $place; }
		public function setUser($user) { $this->user_id = $user->getId(); $this->user = $user; }
	}
?>