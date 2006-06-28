<?php
	class User extends SystemUser
	{
		private $id;
		private $username;
		private $password;
		private $authenticationMethod;

		private $firstname;
		private $lastname;
		private $department;
		private $phone;

		private $roles;

		public function __construct(PDO $PDO,$id = null)
		{
			$this->PDO = $PDO;

			if ($id)
			{
				# Load an existing user
				if (is_numeric($id)) { $sql = "select * from users where id=$id"; }
				else { $sql = "select * from users where username='$id'"; }

				$result = $this->PDO->query($sql);
				if ($user = $result->fetch())
				{
					$this->id = $user['id'];
					$this->username = $user['username'];
					# Passwords are one-way encrypted.  They should not be loaded by default
					$this->authenticationMethod = $user['authenticationMethod'];

					$this->firstname = $user['firstname'];
					$this->lastname = $user['lastname'];
					if ($user['phone']) { $this->phone = $user['phone']; }
					if ($user['department']) { $this->department = $user['department']; }

					$result->closeCursor();
				}
				else { throw new Exception("unknownUser"); }
			}
		}

		public function save()
		{
			# Check for required fields before saving
			if (!$this->username || !$this->firstname || !$this->lastname) { throw new Exception("missingRequiredFields"); }

			$fields = array();
			$fields[] = "username='{$this->username}'";
			$fields[] = "firstname='{$this->firstname}'";
			$fields[] = "lastname='{$this->lastname}'";
			if ($this->authenticationMethod) { $fields[] = "authenticationMethod='{$this->authenticationMethod}'"; } else { $fields[] = "authenticationMethod=null"; }
			if ($this->phone) { $fields[] = "phone='{$this->phone}'"; } else { $fields[] = "phone=null"; }
			if ($this->department) { $fields[] = "department='{$this->department}'"; } else { $fields[] = "department=null"; }
			$fields = implode(",",$fields);

			if ($this->id) { $this->update($fields); }
			else { $this->insert($fields); }

			$this->updateRoles();
		}

		private function update($fields)
		{
			$sql = "update users set $fields where id={$this->id}";

			if (false === $this->PDO->exec($sql)) { $e = $this->PDO->errorInfo(); throw new Exception($sql.$e[2]); }
		}

		private function insert($fields)
		{
			$sql = "insert users set $fields";


			if (false === $this->PDO->exec($sql)) { $e = $this->PDO->errorInfo(); throw new Exception($sql.$e[2]); }
			$this->id = $this->PDO->lastInsertID();

			# Save the initial password if we've added one
			if ($this->getPassword()) { $this->savePassword(); }
		}


		private function updateRoles()
		{
			$roles = $this->getRoles();

			$sql = "delete from user_roles where user_id={$this->id}";
			if (false === $this->PDO->exec($sql)) { $e = $this->PDO->errorInfo(); throw new Exception($sql.$e[2]); }

			$statement = $this->PDO->prepare("insert user_roles set user_id={$this->id},role_id=?");
			foreach($roles as $role_id=>$role)
			{
				if (false === $statement->execute(array("$role_id"))) { $e = $this->PDO->errorInfo(); throw new Exception($sql.$e[2]); }
			}
		}

		protected function saveLocalPassword()
		{
			$sql = "update users set password=md5('{$this->password}') where id={$this->id}";
			if (false === $this->PDO->exec($sql)) { $e = $this->PDO->errorInfo(); throw new Exception($sql.$e[2]); }
		}

		protected function authenticateDatabase($password)
		{
			$sql = "select id from users where username='{$this->username}' and password=md5('$password')";
			$result = $this->PDO->query($sql);
			if ($user = $result->fetch()) { return true; }
			else { return false; }
			$result->closeCursor();
		}
		/* Takes a string or an array of strings and checks if the user has that role */
		public function hasRole($roles)
		{
			if (is_array($roles))
			{
				foreach($roles as $role) { if (in_array($role,$this->getRoles())) { return true; } }
				return false;
			}
			else { return in_array($roles,$this->getRoles()); }
		}

		public function getId() { return $this->id; }
		public function getUsername() { return $this->username; }
		public function getPassword() { return $this->password; }
		public function getAuthenticationMethod() { return $this->authenticationMethod; }
		public function getFirstname() { return $this->firstname; }
		public function getLastname() { return $this->lastname; }
		public function getDepartment() { return $this->department; }
		public function getPhone() { return $this->phone; }

		public function getRoles()
		{
			if (!$this->roles)
			{
				$this->roles = array();
				$sql = "select role_id,role from user_roles left join roles on role_id=id where user_id={$this->id}";
				if ($result = $this->PDO->query($sql))
				{
					foreach($result as $row) { $this->roles[$row['role_id']] = $row['role']; }
					$result->closeCursor();
				}
			}
			return $this->roles;
		}

		public function setUsername($string) { $this->username = sanitizeString($string); }
		public function setPassword($string) { $this->password = $string; }
		public function setAuthenticationMethod($string) { $this->authenticationMethod = $string; }
		public function setFirstname($string) { $this->firstname = sanitizeString($string); }
		public function setLastname($string) { $this->lastname = sanitizeString($string); }
		public function setDepartment($string) { $this->department = sanitizeString($string); }
		public function setPhone($string) { $this->phone = sanitizeString($string); }
		/* Takes an array of role names.  Needs to look up the role_id and add them to the user */
		public function setRoles($array)
		{
			$this->roles = array();
			foreach($array as $id)
			{
				$role = new Role($this->PDO,$id);
				$this->roles[$role->getId()] = $role->getRole();
			}
		}
	}
?>