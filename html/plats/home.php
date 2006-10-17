<?php
/**
 * @copyright Copyright (C) 2006 City of Bloomington, Indiana. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 */
	$template = new Template();
	$template->blocks[] = new Block("plats/findPlatForm.inc");
	if (isset($_GET['plat']))
	{
		$search = array();
		foreach($_GET['plat'] as $field=>$value) { if ($value) { $search[$field] = $value; } }
		if (count($search))
		{
			$template->blocks[] = new Block("plats/findPlatResults.inc",array("response"=>new URL("viewPlat.php"),"platList"=>new PlatList($search)));
		}
	}

	if (userHasRole('Administrator'))
	{
		$template->blocks[] = new Block("plats/addPlatForm.inc");
		if (isset($_POST['plat']))
		{
			$plat = new Plat();
			foreach($_POST['plat'] as $field=>$value)
			{
				$set = "set".ucfirst($field);
				$plat->$set($value);

				try
				{
					$plat->save();
					Header("Location: viewPlat.php?plat_id={$plat->getId()}");
					exit();
				}
				catch (Exception $e) { $_SESSION['errorMessages'][] = $e; }
			}
		}
	}
	$template->render();
?>