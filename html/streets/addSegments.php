<?php
/*
	This is designed to run as a pop up.  When done, it needs to
	refresh the parent window

	$_GET variables:	street_id
	---------------------------------------------------------------------------
	$_POST variables	segments[]	# This comes in from the find form.
									# Used for selecting multiple, pre-existing segments
									# to add to the street


						segment[] 	# This comes in from the add form
									# Used to create a single new segment, and add
									# it to the street
*/
	verifyUser("Administrator");
	$view = new View("popup");

	#--------------------------------------------------------------------------
	# Load the street
	#--------------------------------------------------------------------------
	if (isset($_GET['street_id'])) { $_SESSION['street'] = new Street($_GET['street_id']); }



	#--------------------------------------------------------------------------
	# Show the Find Segment Form
	#--------------------------------------------------------------------------
	$view->blocks[] = new Block("segments/findSegmentForm.inc");
	if (isset($_GET['name']) || isset($_GET['address']))
	{
		$search = array();
		foreach($_GET['name'] as $field=>$value) { if ($value) { $search[$field] = $value; } }
		if (count($search))
		{
			$view->blocks[] = new Block("streets/chooseSegmentsForm.inc",array("streetList"=>new StreetList($search)));
		}
	}

	# Handle any segments that were chosen
	if (isset($_POST['segments']))
	{
		foreach($_POST['segments'] as $segment_id=>$value)
		{
			if ($value == "on")
			{
				$_SESSION['street']->addSegment(new Segment($segment_id));
			}
		}
		try { $_SESSION['street']->save(); }
		catch (Exception $e) { $_SESSION['errorMessages'][] = $e; }
	}


	#--------------------------------------------------------------------------
	# Show the Add Segment Form
	#--------------------------------------------------------------------------
	$view->blocks[] = new Block("segments/addSegmentForm.inc");
	if (isset($_POST['segment']))
	{
		$segment = new Segment();
		foreach($_POST['segment'] as $field=>$value)
		{
			$set = "set".ucfirst($field);
			$segment->$set($value);
		}

		try
		{
			$segment->save();
			$_SESSION['street']->addSegment($segment);
			$_SESSION['street']->save();
		}
		catch (Exception $e) { $_SESSION['errorMessages'][] = $e; }
	}

	$view->render();
?>