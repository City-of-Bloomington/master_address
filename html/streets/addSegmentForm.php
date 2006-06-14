<?php
/*
	This is designed to run as a pop up.  When done, it needs to
	refresh the parent window

	$_GET variables:	street_id
*/
	verifyUser("Administrator");
	include(GLOBAL_INCLUDES."/xhtmlHeader.inc");
	include(APPLICATION_HOME."/includes/popUpBanner.inc");
	include(GLOBAL_INCLUDES."/errorMessages.inc");
?>
<h2>Find Segments</h2>
<form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<fieldset><legend>Name</legend>
	<input name="street_id" type="hidden" value="<?php echo $_GET['street_id']; ?>" />

	<?php include(APPLICATION_HOME."/includes/names/findFields.inc"); ?>

	<button type="submit" class="search">Search</button>
</fieldset>
</form>
<?php
	if (isset($_GET['direction_id']) || isset($_GET['name']) || isset($_GET['suffix_id']) || isset($_GET['postDirection_id']))
	{
		$search = array();
		if ($_GET['direction_id']) { $search['direction_id'] = $_GET['direction_id']; }
		if ($_GET['suffix_id']) { $search['suffix_id'] = $_GET['suffix_id']; }
		if ($_GET['postDirection_id']) { $search['postDirection_id'] = $_GET['postDirection_id']; }
		if ($_GET['name']) { $search['name'] = $_GET['name']; }
		if (count($search))
		{
			#--------------------------------------------------------------------------
			# Find any segments matching their information
			#--------------------------------------------------------------------------
			$streets = new StreetList($search);
			if (count($streets))
			{
				echo "
				<h2>Segments Found</h2>
				<form method=\"post\" action=\"addSegments.php\">
				<fieldset><legend>Choose Segments To Add</legend>
					<input name=\"street_id\" type=\"hidden\" value=\"$_GET[street_id]\" />
					<ul>
				";
					foreach($streets as $street)
					{
						echo "<li>{$street->getFullStreetName()}<ul>";
						$list = new SegmentList(array("street_id"=>$street->getId()));
						foreach($list as $segment)
						{
							echo "
							<li><table>
								<tr><td><input name=\"segments[{$segment->getId()}]\" id=\"segment{$segment->getId()}\" type=\"checkbox\" /></td>
									<td><label for=\"segment{$segment->getId()}\">{$segment->getTag()}</label></td>
									<td>{$segment->getStartingNumber()}</td>
									<td>{$segment->getEndingNumber()}</td>
								</tr>
								</table>
							</li>
							";
						}
						echo "</ul></li>";
					}
				echo "
					</ul>

					<button type=\"submit\" class=\"submit\">Submit</button>
				</fieldset>
				</form>
				";
			}
 			else { echo "<p>No Segments Found</p>"; }
		}
	}
	include(GLOBAL_INCLUDES."/xhtmlFooter.inc");
?>