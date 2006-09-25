<?php
/*
	$_GET variables:	building_id
						place_id
*/
	verifyUser("Administrator");
	$place = new Place($_GET['place_id']);
	$place->deleteBuilding($_GET['building_id']);
	try { $place->save(); }
	catch (Exception $e) { $_SESSION['errorMessages'][] = $e; }

	Header("Location: viewBuilding.php?building_id=$_GET[building_id]");
?>