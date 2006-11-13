<?php
/**
* @copyright Copyright (C) 2006 City of Bloomington, Indiana. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
*/
	class Intersection extends ActiveRecord
	{
		private $id;
		private $tag;
		private $name;
		private $intersectionStatus_id;
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
				$sql = "select * from intersections where id=?";
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
			if (!$this->tag) { throw new Exception("missingRequiredFields"); }


			$fields = array();
			$fields['tag'] = $this->tag ? $this->tag : null;
			$fields['name'] = $this->name ? $this->name : null;
			$fields['intersectionStatus_id'] = $this->intersectionStatus_id ? $this->intersectionStatus_id : null;
			$fields['jurisdiction_id'] = $this->jurisdiction_id ? $this->jurisdiction_id : null;
			$fields['x'] = $this->x ? $this->x : null;
			$fields['y'] = $this->y ? $this->y : null;
			$fields['notes'] = $this->notes ? $this->notes : null;

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

			$sql = "update intersections set $preparedFields where id={$this->id}";
			if (false === $query = $PDO->prepare($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
			if (false === $query->execute($values)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
		}

		private function insert($values,$preparedFields)
		{
			global $PDO;

			$sql = "insert intersections set $preparedFields";
			if (false === $query = $PDO->prepare($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
			if (false === $query->execute($values)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
			$this->id = $PDO->lastInsertID();
		}


		public function getId() { return $this->id; }
		public function getTag() { return $this->tag; }
		public function getName() { return $this->name; }
		public function getIntersectionStatus_id() { return $this->intersectionStatus_id; }
		public function getStatus_id() { return $this->intersectionStatus_id; }
		public function getJurisdiction_id() { return $this->jurisdiction_id; }
		public function getX() { return $this->x; }
		public function getY() { return $this->y; }
		public function getNotes() { return $this->notes; }
		public function getStatus()
		{
			if ($this->intersectionStatus_id)
			{
				if (!$this->status) { $this->status = new Status("intersection",$this->intersectionStatus_id); }
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
		public function setIntersectionStatus_id($int) { $this->status = new Status("intersection",$int); $this->intersectionStatus_id = $int; }
		public function setStatus_id($int) { $this->setIntersectionStatus_id($int); }
		public function setJurisdiction_id($int) { $this->jurisdiction = new Jurisdiction($int); $this->jurisdiction_id = $int; }
		public function setX($int) { $this->x = ereg_replace("[^0-9]","",$int); }
		public function setY($int) { $this->y = ereg_replace("[^0-9]","",$int); }
		public function setNotes($text) { $this->notes = $text; }

		public function setStatus($status) { $this->intersectionStatus_id = $status->getId(); $this->status = $status; }
		public function setJurisdiction($jurisdiction) { $this->jurisdiction_id = $jurisdiction->getId(); $this->jurisdiction = $jurisdiction; }
	}
?>