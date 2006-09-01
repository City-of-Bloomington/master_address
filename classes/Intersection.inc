<?php
/**
* @copyright Copyright (C) 2006 City of Bloomington, Indiana. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
* This file is part of the City of Bloomington's Master Address system.
* Master Address is free software; you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation; either version 2 of the License, or
* (at your option) any later version.
*
* Master Address is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with Master Address; if not, write to the Free Software
* Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA
*/
	class Intersection extends ActiveRecord
	{
		private $id;
		private $tag;
		private $name;
		private $status_id;
		private $jurisdiction_id;
		private $x;
		private $y;
		private $notes;

		private $status;
		private $jurisdiction;

		public function __construct($id=null)
		{
			global $PDO;

			if ($id)
			{
				$sql = "select * from intersections where id=$id";
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
			}
		}


		public function save()
		{
			# Check for required fields here.  Throw an exception if anything is missing.
			if (!$this->tag) { throw new Exception("missingRequiredFields"); }


			# This generates generic SQL that should work right away.
			# You can (and maybe should) replace this $fields code with your own custom SQL
			# for each property of this class,
			$fields = array();
			$fields[] = $this->tag ? "tag='{$this->tag}'" : "tag=null";
			$fields[] = $this->name ? "name='{$this->name}'" : "name=null";
			$fields[] = $this->status_id ? "status_id='{$this->status_id}'" : "status_id=null";
			$fields[] = $this->jurisdiction_id ? "jurisdiction_id='{$this->jurisdiction_id}'" : "jurisdiction_id=null";
			$fields[] = $this->x ? "x='{$this->x}'" : "x=null";
			$fields[] = $this->y ? "y='{$this->y}'" : "y=null";
			$fields[] = $this->notes ? "notes='{$this->notes}'" : "notes=null";

			$fields = implode(",",$fields);


			if ($this->id) { $this->update($fields); }
			else { $this->insert($fields); }
		}

		private function update($fields)
		{
			global $PDO;

			$sql = "update intersections set $fields where id={$this->id}";
			if (false === $PDO->exec($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
		}

		private function insert($fields)
		{
			global $PDO;

			$sql = "insert intersections set $fields";
			if (false === $PDO->exec($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
			$this->id = $PDO->lastInsertID();
		}


		public function getId() { return $this->id; }
		public function getTag() { return $this->tag; }
		public function getName() { return $this->name; }
		public function getStatus_id() { return $this->status_id; }
		public function getJurisdiction_id() { return $this->jurisdiction_id; }
		public function getX() { return $this->x; }
		public function getY() { return $this->y; }
		public function getNotes() { return $this->notes; }
		public function getStatus()
		{
			if ($this->status_id)
			{
				if (!$this->status) { $this->status = new Status($this->status_id); }
				return $this->status;
			}
			else return null;
		}
		public function getJurisdiction()
		{
			if ($this->jurisdiction_id)
			{
				if (!$this->jurisdiction) { $this->jurisdiction = new Jurisdiction($this->jurisdiction_id); }
				return $this->jurisdiction;
			}
			else return null;
		}


		public function setTag($string) { $this->tag = trim($string); }
		public function setName($string) { $this->name = trim($string); }
		public function setStatus_id($int) { $this->status = new Status($int); $this->status_id = $int; }
		public function setJurisdiction_id($int) { $this->jurisdiction = new Jurisdiction($int); $this->jurisdiction_id = $int; }
		public function setX($int) { $this->x = ereg_replace("[^0-9]","",$int); }
		public function setY($int) { $this->y = ereg_replace("[^0-9]","",$int); }
		public function setNotes($text) { $this->notes = $text; }

		public function setStatus($status) { $this->status_id = $status->getId(); $this->status = $status; }
		public function setJurisdiction($jurisdiction) { $this->jurisdiction_id = $jurisdiction->getId(); $this->jurisdiction = $jurisdiction; }
	}
?>