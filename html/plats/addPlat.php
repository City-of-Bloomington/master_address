<?php
/**
 * @copyright Copyright (C) 2006 City of Bloomington, Indiana. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 */
	verifyUser("Administrator");

	$view = new View();
	$view->blocks[] = new Block("plats/addPlatForm.inc");

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

	$view->render();
?>