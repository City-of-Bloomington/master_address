<?php
/*
	$_POST variables	street_id

						segments[]	# Will use the segment_id as the index
*/
	verifyUser("Administrator");

	$street = new Street($PDO,$_POST['street_id']);

	foreach($_POST['segments'] as $segment_id=>$value)
	{
		if ($value == "on")
		{
			$segment = new Segment($PDO,$segment_id);
			$street->addSegment($segment);
		}
	}

	try
	{
		$street->save();
		include(GLOBAL_INCLUDES."/xhtmlHeader.inc");
		echo "
		<head>
			<script type=\"text/javascript\">
				window.opener.location.reload();
				window.close();
			</script>
		</head>
		<body>
		";
		include(GLOBAL_INCLUDE."/xhtmlFooter.inc");
	}
	catch (Exception $e)
	{
		$_SESSION['errorMessages'][] = $e;
		Header("Location: addSegmentForm.php?street_id=$_POST[street_id]");
	}
?>