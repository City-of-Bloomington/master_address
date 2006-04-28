<?php
/*
	Logs a user into the system.
	A logged in user will have a $_SESSION['USER']
								$_SESSION['IP_ADDRESS']


	$_POST Variables:	username
						password
						returnURL
*/
	try
	{
		$user = new User($_POST['username']);

		if ($user->authenticate($_POST['password'])) { $user->startNewSession(); }
		else
		{
			$_SESSION['errorMessages'][] = "wrongPassword";
			Header("Location: ".BASE_URL);
			exit();
		}
	}
	catch (Exception $e)
	{
		$_SESSION['errorMessages'][] = $e->getMessage();
		Header("Location: ".BASE_URL);
		exit();
	}

	#print_r($_SESSION);

	Header("Location: $_POST[returnURL]");
?>
