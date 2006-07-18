<?php
/*
	$_GET variables:	id
	------------------------------------------
	$_POST variables:	id
						status [ status ]
*/
	verifyUser("Administrator");

	if (isset($_POST['status']))
	{
		$status = new Status($_POST['id']);
		$status->setStatus($_POST['status']);

		try
		{
			$status->save();
			Header("Location: home.php");
		}
		catch (Exception $e)
		{
			$_SESSION['errorMessages'][] = $e;

			$view = new View();
			$view->status = $status;
			$view->addBlock("statuses/updateStatusForm.inc");
			$view->render();
		}
	}
	else
	{
		$view = new View();
		$view->status = new Status($_GET['id']);
		$view->addBlock("statuses/updateStatusForm.inc");
		$view->render();
	}
?>