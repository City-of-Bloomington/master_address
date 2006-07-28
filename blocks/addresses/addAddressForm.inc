<h2>Add Address</h2>
<form method="post" action="<?php echo BASE_URL; ?>/addresses/addAddress.php">
	<?php include(APPLICATION_HOME."/includes/addresses/addFormFields.inc"); ?>
<fieldset><legend>Submit</legend>
	<button type="submit" class="submit">Submit</button>
</fieldset>
</form>