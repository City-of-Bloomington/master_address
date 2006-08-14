<h2>Add Address</h2>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	<?php include(APPLICATION_HOME."/includes/addresses/addFormFields.inc"); ?>
<fieldset><legend>Submit</legend>
	<button type="submit" class="submit">Submit</button>
</fieldset>
</form>