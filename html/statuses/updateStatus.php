<?php
/*
	$_GET variables:	type
						id
	------------------------------------------
	$_POST variables:	type
						id
						status [ status ]
*/
	verifyUser("Administrator");
	$view = new View();

	if (isset($_GET['type']) && isset($_GET['id'])) { $view->status = new Status($_GET['type'],$_GET['id']); }
	$view->addBlock("statuses/updateStatusForm.inc");

	if (isset($_POST['status']))
	{
		$status = new Status($_POST['type'],$_POST['id']);
		$status->setStatus($_POST['status']['status']);

		try
		{
			$status->save();
			Header("Location: home.php");
		}
		catch (Exception $e) { $_SESSION['errorMessages'][] = $e; }
	}

	$view->render();
?>