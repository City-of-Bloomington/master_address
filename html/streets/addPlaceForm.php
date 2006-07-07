<?php
/*
	$_GET variables:	street_id
						segment_id
*/
	verifyUser("Administrator");

	#--------------------------------------------------------------------------
	# Load the street and segment
	#--------------------------------------------------------------------------
	if (isset($_POST['street_id'])) { $street = new Street($_POST['street_id']); }
	else { $street = new Street($_GET['street_id']); }

	if (isset($_POST['segment_id'])) { $segment = new Segment($_POST['segment_id']); }
	else { $segment = new Segment($_GET['segment_id']); }


	# We will always need a place object for this page.  The address form requires
	# a place, even if it's an empty one  If we're posting a new place, then we'll
	# actually populate the place object
	$place = new Place();
	if (isset($_POST))
	{
		$PDO->beginTransaction();
		#--------------------------------------------------------------------------
		# Create the new Place
		#--------------------------------------------------------------------------
		if (isset($_POST['place']))
		{
			foreach($_POST['place'] as $field=>$value)
			{
				$set = "set".ucfirst($field);
				$place->$set($value);
			}

			try
			{
				$place->save();

				#--------------------------------------------------------------------------
				# Create the new Address
				#--------------------------------------------------------------------------
				$address = new Address();
				foreach($_POST['address'] as $field=>$value)
				{
					$set = "set".ucfirst($field);
					$address->$set($value);
				}
				$address->setPlace_id($place->getId());

				try { $address->save(); }
				catch (Exception $e) { $PDO->rollBack(); $_SESSION['errorMessages'][] = $e; }
			}
			catch (Exception $e) { $PDO->rollBack(); $_SESSION['errorMessages'][] = $e; }
		}


		if (!isset($_SESSION['errorMessages'])) { $PDO->commit(); }
	}

	#--------------------------------------------------------------------------
	# Refresh the main page and show the forms again
	#--------------------------------------------------------------------------
	include(GLOBAL_INCLUDES."/xhtmlHeader.inc");
	include(APPLICATION_HOME."/includes/popUpBanner.inc");
	include(GLOBAL_INCLUDES."/errorMessages.inc");

	echo "<h1>Add Places</h1>";
	include(APPLICATION_HOME."/includes/streets/addPlaceForm.inc");

	include(GLOBAL_INCLUDES."/xhtmlFooter.inc");
?>