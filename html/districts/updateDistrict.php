<?php
/*
	$_GET variables:	id
	------------------------------------------------------
	$_POST variables:	id
						district [	name
									districtType_id
								]
*/
	verifyUser("Administrator");
	$template = new Template();
	$form = new Block("districts/updateDistrictForm.inc");
	if (isset($_GET['id'])) { $form->district = new District($_GET['id']); }

	if (isset($_POST['district']))
	{
		$district = new District($_POST['id']);
		foreach($_POST['district'] as $field=>$value)
		{
			$set = "set".ucfirst($field);
			$district->$set($value);
		}
		try
		{
			$district->save();
			Header("Location: viewDistrict.php?id=$_POST[id]");
		}
		catch (Exception $e)
		{
			$_SESSION['errorMessages'][] = $e;
			$form->district = $district;
		}
	}

	$template->blocks[] = $form;
	$template->render();
?>