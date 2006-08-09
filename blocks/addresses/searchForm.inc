<h1>Find an Address</h1>
<form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<fieldset><legend>Quick Search</legend>
	<input name="fullAddress" value="<?php if(isset($_GET['fullAddress'])) echo $_GET['fullAddress']; ?>" />

	<button type="submit" class="search">Search</button>
</fieldset>
</form>