<?php
/*
	$_POST variables:	id						startMonth
						town_id					startDay
						name					startYear
						direction_id			endMonth
						suffix_id				endDay
						postDirection_id		endYear
						notes
*/
	verifyUser("Administrator");

	require_once(APPLICATION_HOME."/classes/Name.inc");
	$name = new Name($_POST['id']);
	$name->setName($_POST['name']);
	$name->setDirection_id($_POST['direction_id']);
	$name->setPostDirection_id($_POST['direction_id']);
	$name->setSuffix_id($_POST['suffix_id']);
	if ($_POST['startYear'] && $_POST['startMonth'] && $_POST['startDay']) { $name->setStartDate("$_POST[startYear]-$_POST[startMonth]-$_POST[startDay]"); }
	if ($_POST['endYear'] && $_POST['endMonth'] && $_POST['endDay']) { $name->setEndDate("$_POST[endYear]-$_POST[endMonth]-$_POST[endDay]"); }
	$name->setNotes($_POST['notes']);

	try
	{
		$name->save();
		Header("Location: home.php");
	}
	catch (Exception $e)
	{
		$_SESSION['errorMessages'][] = $e;
		Header("Location: updateNameForm.php?id=$_POST[id]");
	}
?>