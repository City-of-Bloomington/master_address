<?php
/*
	$_GET variables:	name
*/
	$view = new View();
	$view->addBlock("names/findNameForm.inc");

	if (isset($_GET['name']))
	{
		$search = array();
		foreach($_GET['name'] as $field=>$value) { if ($value) { $search[$field] = $value; } }
		if (count($search))
		{
			$view->response = new URL("viewName.php");
			$view->nameList = new NameList($search);
			$view->addBlock("names/findNameResults.inc");
		}
	}

	$view->return_url = BASE_URL."/names/viewName.php?id=";
	$view->render();
?>