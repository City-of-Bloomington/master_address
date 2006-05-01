<?php
	class StatusCode
	{
		private $statusCode;
		private $status;



		public function __construct($statusCode=null)
		{
			global $PDO;

			if ($statusCode)
			{
				$sql = "select * from statusCodes where statusCode=$statusCode";
				$result = $PDO->query($sql);
				if ($row = $result->fetch())
				{
					$this->statusCode = $statusCode;
					$this->status = $row['status'];

					$result->closeCursor();
				}
				else { throw new Exception($sql); }
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
			if (!$this->status) { throw new Exception("missingRequiredFields"); }


			if ($this->statusCode) { $this->update(); }
			else { $this->insert(); }
		}

		private function update()
		{
			global $PDO;

			$sql = "update statusCodes set status='{$this->status}' where statusCode={$this->statusCode}";
			if (false === $PDO->exec($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
		}

		private function insert()
		{
			global $PDO;

			$sql = "insert statusCodes set status='{$this->status}'";
			if (false === $PDO->exec($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
			$this->statusCode = $PDO->lastInsertID();
		}


		public function getStatusCode() { return $this->statusCode; }
		public function getStatus() { return $this->status; }


		public function setStatus($string) { $this->status = sanitizeString($string); }
	}
?>