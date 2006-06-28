<div>
	<form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	<fieldset><legend>Quick Search</legend>
		<input name="fullAddress" value="<?php if(isset($_GET['fullAddress'])) echo $_GET['fullAddress']; ?>" />

		<button type="submit" class="search">Search</button>
	</fieldset>
	</form>
</div>
<?php
	if (isset($_GET['fullAddress']))
	{
		echo "<div><table>";
		require_once(APPLICATION_HOME."/classes/AddressSearch.inc");
		$search = new AddressSearch();
		$search->find(array("fullAddress"=>$_GET['fullAddress']));

		if (!isset($_GET['page'])) { $_GET['page'] = 0; }
		$pages = $search->getPagination(50);
		$iterator = new LimitIterator($search->getIterator(),$pages[$_GET['page']],$pages->getPageSize());

		$href = BASE_URL."/addresses/viewAddress.php";
		foreach($iterator as $result)
		{
			echo "<tr><td><a href=\"$href?id={$result->getAddress_id()}\">{$result->getFullAddress()}</a></td></tr>";
		}
		echo "</table></div>";

		include(APPLICATION_HOME."/includes/pageNavigation.inc");
	}
?>