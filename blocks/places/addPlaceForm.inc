<h1>Add a new Place</h1>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	<?php include(APPLICATION_HOME."/blocks/places/addFormFields.inc"); ?>
<fieldset>
	<button type="submit" class="submit">Submit</button>
	<button type="button" class="cancel" onclick="document.location.href='home.php';">Cancel</button>
</fieldset>
</form>
