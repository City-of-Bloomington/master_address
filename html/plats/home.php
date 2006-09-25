<?php
	$view = new View();
	$view->blocks[] = new Block("plats/findPlatForm.inc");
	if (isset($_GET['plat']))
	{
		$search = array();
		foreach($_GET['plat'] as $field=>$value) { if ($value) { $search[$field] = $value; } }
		if (count($search))
		{
			$view->blocks[] = new Block("plats/findPlatResults.inc",array("response"=>new URL("viewPlat.php"),"platList"=>new PlatList($search)));
		}
	}
	$view->render();
?>