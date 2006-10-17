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
	$template = new Template();

	if (isset($_GET['type']) && isset($_GET['id'])) { $template->status = new Status($_GET['type'],$_GET['id']); }
	$template->addBlock("statuses/updateStatusForm.inc");

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

	$template->render();
?>