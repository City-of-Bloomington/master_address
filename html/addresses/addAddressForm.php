<?php
/*
	$_GET variables:	place_id

						street_id
						segment_id
	------------------------------------------------
	$_POST variables:	address[	street_id
									place_id
									segment_id

									number			status_id
									addressType		active
									city_id			startDate
									zip				endDate
									zipplus4		notes
							]
*/
	verifyUser("Administrator");

	include(GLOBAL_INCLUDES."/xhtmlHeader.inc");
	include(APPLICATION_HOME."/includes/popUpBanner.inc");

	if (isset($_POST['address']))
	{
		$place = new Place($_POST['place_id']);
		$street = new Street($_POST['street_id']);
		$segment = new Segment($_POST['segment_id']);
	}
	else
	{
		if (isset($_GET['place_id'])) { $place = new Place($_GET['place_id']); }
		else  { $_SESSION['errorMessages'][] = new Exception("missingRequiredFields"); }

		include(GLOBAL_INCLUDES."/errorMessages.inc");

		if (!isset($_GET['street_id']) || !isset($_GET['segment_id']))
		{
			$return_url = BASE_URL."/addresses/addAddressForm.php?place_id={$place->getId()}";
			include(APPLICATION_HOME."/includes/segments/findSegmentForm.inc");
		}
		else
		{
			$street = new Street($_GET['street_id']);
			$segment = new Segment($_GET['segment_id']);
			include(APPLICATION_HOME."/includes/addresses/addAddressForm.inc");
		}
	}

	include(GLOBAL_INCLUDES."/xhtmlFooter.inc");
?>