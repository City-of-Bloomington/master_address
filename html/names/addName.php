<?php
/*
	$_POST variables:	return_url
						error_url

						name[	direction_id
								name
								suffix_id
								postDirection_id
								town_id
								notes
							]

						startMonth	endMonth
						startDay	endDay
						startYear	endYear
*/
	verifyUser("Administrator");

	$name = new Name($PDO);
	$name->setDirection_id($_POST['name']['direction_id']);
	$name->setName($_POST['name']['name']);
	$name->setSuffix_id($_POST['name']['suffix_id']);
	$name->setPostDirection_id($_POST['name']['postDirection_id']);
	$name->setTown_id($_POST['name']['town_id']);
	$name->setNotes($_POST['name']['notes']);
	$name->setStartDate("$_POST[startYear]-$_POST[startMonth]-$_POST[startDay]");
	if ($_POST['endYear'] && $_POST['endMonth'] && $_POST['endDay']) { $name->setEndDate("$_POST[endYear]-$_POST[endMonth]-$_POST[endDay]"); }


	try { $name->save(); }
	catch (Exception $e)
	{
		$_SESSION['errorMessages'][] = $e;
		Header("Location: $_POST[error_url]");
		exit();
	}

	Header("Location: $_POST[return_url]{$name->getId()}");
?>