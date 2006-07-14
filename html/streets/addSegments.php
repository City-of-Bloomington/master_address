<?php
/*
	This is designed to run as a pop up.  When done, it needs to
	refresh the parent window

	$_GET variables:	street_id
	---------------------------------------------------------------------------
	$_POST variables	street_id

						segments[]	# This comes in from the find form.
									# Used for selecting multiple, pre-existing segments
									# to add to the street


						segment[] 	# This comes in from the add form
									# Used to create a single new segment, and add
									# it to the street
*/
	verifyUser("Administrator");

	#--------------------------------------------------------------------------
	# Load the street
	#--------------------------------------------------------------------------
	if (isset($_POST['street_id'])) { $street = new Street($_POST['street_id']); }
	else { $street = new Street($_GET['street_id']); }

	#--------------------------------------------------------------------------
	# Handle multiple segments from the find form
	#--------------------------------------------------------------------------
	if (isset($_POST['segments']))
	{
		foreach($_POST['segments'] as $segment_id=>$value)
		{
			if ($value == "on")
			{
				$segment = new Segment($segment_id);
				$street->addSegment($segment);
			}
		}
		try { $street->save(); }
		catch (Exception $e) { $_SESSION['errorMessages'][] = $e; }
	}

	#--------------------------------------------------------------------------
	# Handle a new segment from the add form
	#--------------------------------------------------------------------------
	if (isset($_POST['segment']))
	{
		try { $street->save(); }
		catch (Exception $e) { $_SESSION['errorMessages'][] = $e; }
	}


	#--------------------------------------------------------------------------
	# Refresh the main page and show the forms again
	#--------------------------------------------------------------------------
	include(GLOBAL_INCLUDES."/xhtmlHeader.inc");
	include(APPLICATION_HOME."/includes/popUpBanner.inc");
	include(GLOBAL_INCLUDES."/errorMessages.inc");

	echo "<h1>Add Segments</h1>";

	include(APPLICATION_HOME."/includes/streets/findSegmentForm.inc");
	include(APPLICATION_HOME."/includes/segments/addSegmentForm.inc");

	include(GLOBAL_INCLUDE."/xhtmlFooter.inc");
?>