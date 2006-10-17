<?php
/*
	$_GET variables:	street_id
						segment_id
*/
	verifyUser("Administrator");
	$template = new Template("popup");

	if (isset($_GET['street_id'])) { $_SESSION['street'] = new Street($_GET['street_id']); }
	if (isset($_GET['segment_id'])) { $_SESSION['segment'] = new Segment($_GET['segment_id']); }


	$template->blocks[] = new Block("streets/addPlaceAddressForm.inc");
	if (isset($_POST['place']) && isset($_POST['address']))
	{
		$PDO->beginTransaction();
			# Create the new Place
			$place = new Place();
			foreach($_POST['place'] as $field=>$value)
			{
				$set = "set".ucfirst($field);
				$place->$set($value);
			}
			try
			{
				$place->save();

				# Create the new Address
				$address = new Address();
				foreach($_POST['address'] as $field=>$value)
				{
					$set = "set".ucfirst($field);
					$address->$set($value);
				}
				$address->setPlace($place);
				$address->setStreet($_SESSION['street']);
				$address->setSegment($_SESSION['segment']);

				try { $address->save(); }
				catch (Exception $e)
				{
					$_SESSION['errorMessages'][] = $e;
					$PDO->rollBack();
				}
			}
			catch (Exception $e)
			{
				$_SESSION['errorMessages'][] = $e;
				$PDO->rollBack();
			}
		if (!isset($_SESSION['errorMessages'])) { $PDO->commit(); }
	}

	$template->render();
?>