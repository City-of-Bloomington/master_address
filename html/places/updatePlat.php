<?php
/*
	$_GET variables:	place_id

						plat_id
						plat [ ]
*/
	verifyUser("Administrator");
	$view = new View();

	# If we've got a place and a plat, update the place and send em
	# back to View the Place
	if (isset($_GET['place_id'])) { $_SESSION['place'] = new Place($_GET['place_id']); }
	if (isset($_GET['plat_id']))
	{
		$_SESSION['place']->setPlat_id($_GET['plat_id']);
		$_SESSION['place']->save();

		$place_id = $_SESSION['place']->getId();
		unset($_SESSION['place']);

		Header("Location: viewPlace.php?place_id=$_GET[place_id]");
		exit();
	}


	# We don't have a plat yet, keep showing the necessary forms
	$view->blocks[] = new Block("places/placeInfo.inc",array('place'=>$_SESSION['place']));
	$view->blocks[] = new Block("plats/findPlatForm.inc");
	if (isset($_GET['plat']))
	{
		$search = array();
		foreach($_GET['plat'] as $field=>$value) { if ($value) { $search[$field] = $value; } }
		if (count($search))
		{
			$view->blocks[] = new Block("plats/findPlatResults.inc",
										array("response"=>new URL("updatePlat.php?place_id={$_SESSION['place']->getId()}"),
												"platList"=>new PlatList($search)));
		}
	}

	$view->render();
?>