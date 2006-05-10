<div>
	<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	<fieldset><legend>Quick Search</legend>
		<input name="fullAddress" value="<?php if(isset($_POST['fullAddress'])) echo $_POST['fullAddress']; ?>" />

		<button type="submit" class="search">Search</button>
	</fieldset>
	</form>
</div>
<?php
	if (isset($_POST['fullAddress']))
	{
		echo "<div><table>";
		require_once(APPLICATION_HOME."/classes/AddressSearch.inc");
		$search = new AddressSearch();
		$search->find(array("fullAddress"=>$_POST['fullAddress']));
		foreach($search as $result)
		{
			echo "<tr><td>{$result->getFullAddress()}</td></tr>";
		}
		echo "</table></div>";
	}
?>