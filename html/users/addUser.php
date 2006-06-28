<?php
/*
	$_POST variables:	authenticationMethod
						username
						roles

						# May be optional if LDAP is used
						password

						firstname
						lastname
						department
						phone
*/
	verifyUser("Administrator");

	#--------------------------------------------------------------------------
	# Create the new account
	#--------------------------------------------------------------------------
	$user = new User($PDO);
	$user->setAuthenticationMethod($_POST['authenticationMethod']);
	$user->setUsername($_POST['username']);
	if ($_POST['password']) { $user->setPassword($_POST['password']); }
	if (isset($_POST['roles'])) { $user->setRoles($_POST['roles']); }

	if ($_POST['authenticationMethod'] == "LDAP")
	{
		# Load the rest of their stuff from LDAP
		$ldap = new LDAPEntry($user->getUsername());
		$user->setFirstname($ldap->getFirstname());
		$user->setLastname($ldap->getLastname());
		$user->setDepartment($ldap->getDepartment());
		$user->setPhone($ldap->getPhone());
	}
	else
	{
		$user->setFirstname($_POST['firstname']);
		$user->setLastname($_POST['lastname']);
		$user->setDepartment($_POST['department']);
		$user->setPhone($_POST['phone']);
	}

	try
	{
		$user->save();
		Header("Location: home.php");
	}
	catch (Exception $e)
	{
		$_SESSION['errorMessages'][] = $e;
		Header("Location: addUserForm.php");
	}
?>