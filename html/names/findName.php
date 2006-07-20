<?php
/*
	$_GET variables:	name 	# Only if the find form is submitted
*/
	$view = new View();
	$view->return_url = BASE_URL."/names/viewName.php?id=";
	$view->addBlock("names/findNameForm.inc");
	$view->render();
?>