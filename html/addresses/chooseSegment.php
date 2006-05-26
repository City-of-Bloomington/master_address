<?php
/*
	$_GET variables:	number
						suffix

						direction_id
						street_name
						suffix_id
						postDirection_id
*/
	#--------------------------------------------------------------------------
	# We absolutely have to have a street name and number
	#--------------------------------------------------------------------------
	if (!$_GET['number'] || !$_GET['name'])
	{
		#$_SESSION['errorMessages'][] = new Exception("missingRequiredFields");
		#Header("Location: findSegmentForm.php");
		#exit();
		die(print_r($_GET));
	}

	#--------------------------------------------------------------------------
	# Find any segments matching their information
	#--------------------------------------------------------------------------
	$search = array();
	if ($_GET['direction_id']) { $search['direction_id'] = $_GET['direction_id']; }
	if ($_GET['suffix_id']) { $search['suffix_id'] = $_GET['suffix_id']; }
	if ($_GET['postDirection_id']) { $search['postDirection_id'] = $_GET['postDirection_id']; }
	$search['name'] = $_GET['name'];

	$segments = array();
	$nameList = new NameList();
	$nameList->find($search);
	foreach($nameList as $name)
	{
		$streets = $name->getStreets();
		foreach($streets as $street)
		{
			$list = new SegmentList();
			$list->find(array("street_id"=>$street->getId(),"number"=>$_GET['number']));
			foreach($list as $segment) { $segments[] = $segment; }
		}
	}

	#--------------------------------------------------------------------------
	# We can't add an address without a segment.
	#--------------------------------------------------------------------------
	if (count($segments)==0)
	{
		echo "<p>Could not find any matching segments</p>";
		$_SESSION['errorMessages'][] = new Exception("Could not find any matching segments");
		Header("Location: findSegmentForm.php");
		exit();
	}
	#--------------------------------------------------------------------------
	# If we found only one segment, we're good to go.  Just send them on to
	# the add address form
	#--------------------------------------------------------------------------
	if (count($segments)==1)
	{
		Header("Location: addAddressForm.php?number=$_GET[number];suffix=$_GET[suffix];segment_id=".$segments[0]->getId());
		exit();
	}

	#--------------------------------------------------------------------------
	# We've got a bunch of segments, and we need them to choose one
	#--------------------------------------------------------------------------
	include(GLOBAL_INCLUDES."/xhtmlHeader.inc");
	include(APPLICATION_HOME."/includes/banner.inc");
	include(APPLICATION_HOME."/includes/menubar.inc");
	include(APPLICATION_HOME."/includes/sidebar.inc");
?>
<div id="mainContent">
	<h1>Choose a Segment</h1>
	<h2><?php
			echo "$_GET[number] $_GET[suffix]";
			if ($_GET['direction_id']) { $direction = new Direction($_GET['direction_id']); echo $direction; }
			echo sanitizeString($_GET['name']);
			if ($_GET['suffix_id']) { $suffix = new Suffix($_GET['suffix_id']); echo $suffix; }
			if ($_GET['postDirection_id']) { $post = new Direction($_GET['postDirection_id']); echo $post; }
		?>
	</h2>
	<table>
	<?php
		foreach($segments as $segment)
		{
			echo "
			<tr><td><a href=\"addAddressForm.php?number=$_GET[number];suffix=$_GET[suffix];segment_id={$segment->getId()}\">{$segment->getTag()}</a></td>
				<td><a href=\"addAddressForm.php?number=$_GET[number];suffix=$_GET[suffix];segment_id={$segment->getId()}\">{$segment->getFullStreetName()}</a></td>
				<td><a href=\"addAddressForm.php?number=$_GET[number];suffix=$_GET[suffix];segment_id={$segment->getId()}\">{$segment->getTown()->getName()}</a></td></tr>
			";
		}
	?>
	</table>
</div>
<?php
	include(APPLICATION_HOME."/includes/footer.inc");
	include(GLOBAL_INCLUDES."/xhtmlFooter.inc");
?>