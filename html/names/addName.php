<?php
/*
	$_POST variables:	direction_id		startMonth
						name				startDay
						suffix_id			startYear
						postDirection_id	endMonth
						town_id				endDay
						notes				endYear
*/
	verifyUser("Administrator");

	$name = new Name();
	$name->setDirection_id($_POST['direction_id']);
	$name->setName($_POST['name']);
	$name->setSuffix_id($_POST['suffix_id']);
	$name->setPostDirection_id($_POST['postDirection_id']);
	$name->setTown_id($_POST['town_id']);
	$name->setNotes($_POST['notes']);
	$name->setStartDate("$_POST[startYear]-$_POST[startMonth]-$_POST[startDay]");
	if ($_POST['endYear'] && $_POST['endMonth'] && $_POST['endDay']) { $name->setEndDate("$_POST[endYear]-$_POST[endMonth]-$_POST[endDay]"); }

	try { $name->save(); }
	catch (Exception $e)
	{
		$_SESSION['errorMessages'][] = $e;
		Header("Location: findNameForm.php");
		exit();
	}

	Header("Location: viewName.php?id={$name->getId()}");
?>