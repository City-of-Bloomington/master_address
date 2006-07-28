<?php
/*
	$_GET variables:	street_id
						segment_id
*/
	verifyUser("Administrator");

	if (isset($_GET['street_id'])) { $_SESSION['street'] = new Street($_GET['street_id']); }
	if (isset($_GET['segment_id'])) { $_SESSION['segment'] = new Segment($_GET['segment_id']); }

	$view = new View("popup");

	$view->addBlock("streets/addPlaceAddressForm.inc");
	if (isset($_POST['place']) && isset($_POST['address']))
	{
		$PDO->beginTransaction();
			$place = new Place();
			foreach($_POST['place'] as $field=>$value)
			{
				$set = "set".ucfirst($field);
				$place->$set($value);
			}

			try
			{
				$place->save();

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
		$PDO->commit();
	}

	$view->render();
?>