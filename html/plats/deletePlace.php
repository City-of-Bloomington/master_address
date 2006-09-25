<?php
/*
	$_GET variables:	plat_id
						place_id
*/
	verifyUser("Administrator");
	$place = new Place($_GET['place_id']);
	$place->deletePlat();
	$place->save();

	Header("Location: viewPlat.php?plat_id=$_GET[plat_id]");
?>