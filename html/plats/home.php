<?php
	$view = new View();
	$view->addBlock("plats/findPlatForm.inc");
	if (isset($_GET['plat']))
	{
		$search = array();
		foreach($_GET['plat'] as $field=>$value) { if ($value) { $search[$field] = $value; } }
		if (count($search))
		{
			$view->response = new URL("viewPlat.php");
			$view->platList = new PlatList($search);
			$view->addBlock("plats/findPlatResults.inc");
		}
	}
	$view->render();
?>