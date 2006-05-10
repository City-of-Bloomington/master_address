<?php
/*
	$_POST variables:	id
						authenticationMethod
						username
						roles
						firstname
						lastname

						# Optional
						password
						department
						phone
*/
	verifyUser("Administrator");

	#--------------------------------------------------------------------------
	# Update the account
	#--------------------------------------------------------------------------
	$user = new User($_POST['id']);
	$user->setAuthenticationMethod($_POST['authenticationMethod']);
	$user->setUsername($_POST['username']);
	$user->setFirstname($_POST['firstname']);
	$user->setLastname($_POST['lastname']);
	$user->setDepartment($_POST['department']);
	$user->setPhone($_POST['phone']);

	# Only update the password if they actually typed somethign in
	if ($_POST['password']) { $user->setPassword($_POST['password']); }
	if (isset($_POST['roles'])) { $user->setRoles($_POST['roles']); }

	try
	{
		$user->save();
		Header("Location: home.php");
	}
	catch (Exception $e)
	{
		$_SESSION['errorMessages'][] = $e;
		Header("Location: updateUserForm.php");
	}
?>