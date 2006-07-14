<?php
	include(GLOBAL_INCLUDES."/xhtmlHeader.inc");
	include(APPLICATION_HOME."/includes/banner.inc");
	include(APPLICATION_HOME."/includes/menubar.inc");
	include(APPLICATION_HOME."/includes/sidebar.inc");
?>
<div id="mainContent">
	<h1>Find a Street</h1>
	<form method="get" action="findStreetForm.php">
		<?php include(APPLICATION_HOME."/includes/names/findFields.inc"); ?>
	<fieldset><legend>Submit</legend>
		<button type="submit" class="search">Search</button>
	</fieldset>
	</form>
	<?php
		if (isset($_GET['name']))
		{
			$search = array();
			foreach($_GET['name'] as $field=>$value) { if ($value) { $search[$field] = $value; } }
			if (count($search))
			{
				$streetList = new StreetList($search);
				switch (count($streetList))
				{
					case 0:
						echo "<p>No Streets Found</p>";
					break;

					default:
						echo "<table>";
						foreach($streetList as $street)
						{
							echo "
							<tr><td><a href=\"viewStreet.php?id={$street->getId()}\">{$street->getId()}</a></td>
								<td><a href=\"viewStreet.php?id={$street->getId()}\">{$street->getFullStreetName()}</a></td></tr>
							";
						}
						echo "</table>";
				}
			}
		}
	?>
</div>
<?php
	include(APPLICATION_HOME."/includes/footer.inc");
	include(GLOBAL_INCLUDES."/xhtmlFooter.inc");
?>