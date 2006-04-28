<?php
	require_once(GLOBAL_INCLUDES."/classes/SystemUser.inc");

	class User extends SystemUser
	{
		private $firstname;
		private $lastname;
		private $department;
		private $phone;

		public function __construct($id = null)
		{
			global $PDO;

			if ($id)
			{
				# Load an existing user
				if (is_numeric($id)) { $sql = "select * from users where userID=$id"; }
				else { $sql = "select * from users where username='$id'"; }

				$result = $PDO->query($sql);
				if ($user = $result->fetch())
				{
					$this->setUserID($user['userID']);
					$this->setUsername($user['username']);
					# Passwords are one-way encrypted.  They should not be loaded by default
					$this->setAuthenticationMethod($user['authenticationMethod']);

					$this->firstname = $user['firstname'];
					$this->lastname = $user['lastname'];
					if ($user['phone']) { $this->phone = $user['phone']; }
					if ($user['department']) { $this->department = $user['department']; }

					$result->closeCursor();
					$this->loadRoles();
				}
				else { throw new Exception($sql); }
			}
		}
		public function save()
		{
			# Check for required fields before saving
			if (!$this->getUsername() || !$this->firstname || !$this->lastname) { throw new Exception("missingRequiredFields"); }

			if ($this->getUserID()) { $this->update(); }
			else { $this->insert(); }

			$this->updateRoles();
		}
		private function update()
		{
			global $PDO;

			$sql = "update users set username='{$this->getUsername()}',authenticationMethod='{$this->getAuthenticationMethod()}',
					firstname='{$this->getFirstname()}',lastname='{$this->getLastname()}'";

			if ($this->getPhone()) { $sql.=",phone='{$this->getPhone()}'"; } else { $sql.=",phone=null"; }
			if ($this->getDepartment()) { $sql.=",department='{$this->getDepartment()}'"; } else { $sql.=",department=null"; }

			$sql.=" where userID=".$this->getUserID();
			if (false === $PDO->exec($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
		}

		private function insert()
		{
			global $PDO;

			$sql = "insert users set username='{$this->getUsername()}',authenticationMethod='{$this->getAuthenticationMethod()}',
					firstname='{$this->getFirstname()}',lastname='{$this->getLastname()}'";

			if ($this->getPhone()) { $sql.=",phone='{$this->getPhone()}'"; } else { $sql.=",phone=null"; }
			if ($this->getDepartment()) { $sql.=",department='{$this->getDepartment()}'"; } else { $sql.=",department=null"; }

			if (false === $PDO->exec($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
			$this->setUserID($PDO->lastInsertID());

			# Save the initial password if we've added one
			if ($this->getPassword()) { $this->savePassword(); }
		}

		protected function loadRoles()
		{
			global $PDO;

			$roles = array();
			$sql = "select role from userRoles where userID={$this->getUserID()}";
			if ($result = $PDO->query($sql))
			{
				foreach($result as $row) { $roles[] = $row['role']; }
				$this->setRoles($roles);
				$result->closeCursor();
			}
		}

		private function updateRoles()
		{
			global $PDO;

			$sql = "delete from userRoles where userID={$this->getUserID()}";
			$PDO->exec($sql);

			$roles = $this->getRoles();

			$statement = $PDO->prepare("insert userRoles set userID={$this->getUserID()},role=?");
			foreach($roles as $role) { $statement->execute(array("$role")); }
		}

		protected function saveLocalPassword()
		{
			global $PDO;

			$sql = "update users set password=md5('{$this->getPassword()}') where userID={$this->getUserID()}";
			if (false === $PDO->exec($sql)) { $e = $PDO->errorInfo(); throw new Exception($sql.$e[2]); }
		}

		protected function authenticateDatabase($password)
		{
			global $PDO;

			$sql = "select userID from users where username='{$this->getUsername()}' and password=md5('$password')";
			$result = $PDO->query($sql);
			if ($user = $result->fetch()) { return true; }
			else { return false; }
			$result->closeCursor();
		}

		public function getFirstname() { return $this->firstname; }
		public function getLastname() { return $this->lastname; }
		public function getDepartment() { return $this->department; }
		public function getPhone() { return $this->phone; }


		public function setFirstname($string) { $this->firstname = sanitizeString($string); }
		public function setLastname($string) { $this->lastname = sanitizeString($string); }
		public function setDepartment($string) { $this->department = sanitizeString($string); }
		public function setPhone($string) { $this->phone = sanitizeString($string); }
	}
?>