<?php
/*
	$_GET variables:	direction_id
						name
						suffix_id
						postDirection_id
*/
?>
<h2>Find a Street</h2>
<form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<fieldset><legend>Name</legend>
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
			$streetList = new StreetList($search);
			if (count($streetList))
			{
				echo "<h2>Streets Found</h2><table><tr><th></th><th>ID</th><th>Name</th></tr>";
				foreach($streetList as $street)
				{
					echo "<tr>";
						if ( isset($_SESSION['USER']) && in_array("Administrator",$_SESSION['USER']->getRoles()) ) { echo "<td><button type=\"button\" class=\"editSmall\" onclick=\"document.location.href='updateStreetForm.php?id={$street->getId()}'\">Edit</button></td>"; }
						else { echo "<td></td>"; }
					echo "
						<td><a href=\"".BASE_URL."/streets/viewStreet.php?id={$street->getId()}\">{$street->getId()}</a></td>
						<td><a href=\"".BASE_URL."/streets/viewStreet.php?id={$street->getId()}\">{$street->getFullStreetName()}</a></td>
					</tr>
					";
				}
				echo "</table>";
			}
			else { echo "<p>No Streets Found</p>"; }
		}
	}
?>