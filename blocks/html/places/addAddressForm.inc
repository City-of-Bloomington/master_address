<h2>Add a new Address</h2>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	<?php include(APPLICATION_HOME."/includes/addresses/addFormFields.inc"); ?>
<fieldset><legend>Submit</legend>
	<button type="submit" class="submit">Submit</button>
	<button type="button" class="cancel" onclick="document.location.href='<?php echo BASE_URL; ?>/places/viewPlace.php?place_id=<?php echo $_SESSION['place']->getId(); ?>';">Cancel</button>
</fieldset>
</form>