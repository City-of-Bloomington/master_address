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
	<h1>Find a Name</h1>
	<form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	<fieldset><legend>Name info</legend>
		<?php include(APPLICATION_HOME."/includes/names/findFields.inc"); ?>

		<table>
		<tr><td><label for="town_id">Town</label></td>
			<td><select name="town_id"><option></option>
				<?php
					$towns = new TownList();
					$towns->find();
					foreach($towns as $town)
					{
						if (isset($_GET['town_id']) && $_GET['town_id']==$town->getId()) { echo "<option value\"{$town->getId()}\" selected=\"selected\">{$town->getName()}</option>"; }
						else { echo "<option value=\"{$town->getId()}\">{$town->getName()}</option>"; }
					}
				?>
				</select>
			</td>
		</tr>
		</table>

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
	?>
</div>
<?php
	include(APPLICATION_HOME."/includes/footer.inc");
	include(GLOBAL_INCLUDES."/xhtmlFooter.inc");
?>