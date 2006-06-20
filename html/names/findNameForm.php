<?php
/*
	$_GET variables:	direction_id		town_id
						name
						suffix_id
						postDirection_id
*/
	include(GLOBAL_INCLUDES."/xhtmlHeader.inc");
	include(APPLICATION_HOME."/includes/banner.inc");
	include(APPLICATION_HOME."/includes/menubar.inc");
	include(APPLICATION_HOME."/includes/sidebar.inc");
?>
<div id="mainContent">
	<?php include(GLOBAL_INCLUDES."/errorMessages.inc"); ?>

	<p>Names can be used for multiple streets.</p>

	<h1>Find a Name</h1>
	<form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	<fieldset><legend>Name info</legend>
		<?php include(APPLICATION_HOME."/includes/names/findFields.inc"); ?>

		<button type="submit" class="search">Search</button>
	</fieldset>
	</form>

	<?php
		if (isset($_GET['direction_id']) || isset($_GET['name']) || isset($_GET['suffix_id']) || isset($_GET['postDirection_id']) || isset($_GET['town_id']))
		{
			$search = array();
			if ($_GET['direction_id']) { $search['direction_id'] = $_GET['direction_id']; }
			if ($_GET['suffix_id']) { $search['suffix_id'] = $_GET['suffix_id']; }
			if ($_GET['postDirection_id']) { $search['postDirection_id'] = $_GET['postDirection_id']; }
			if ($_GET['name']) { $search['name'] = $_GET['name']; }
			if ($_GET['town_id']) { $search['town_id'] = $_GET['town_id']; }
			if (count($search))
			{
				$nameList = new NameList($search);
				if (count($nameList))
				{
					echo "<table>";
					foreach($nameList as $name)
					{
						echo "<tr><td><a href=\"viewName.php?id={$name->getId()}\">{$name->getFullname()}</a></td></tr>";
					}
					echo "</table>";
				}
				else { echo "<p>No Names Found</p>"; }
			}
		}


		if (userHasRole("Administrator"))
		{
			echo "<h1>Add a new Name</h1>";
			include(APPLICATION_HOME."/includes/names/addNameForm.inc");
		}
	?>
</div>
<?php
	include(APPLICATION_HOME."/includes/footer.inc");
	include(GLOBAL_INCLUDES."/xhtmlFooter.inc");
?>