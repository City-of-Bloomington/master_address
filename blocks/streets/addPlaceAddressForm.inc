<p>Use this form to add multiple places with addresses.  Close this window when you are done.</p>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	<?php
		include(APPLICATION_HOME."/includes/addresses/addFormFields.inc");
		include(APPLICATION_HOME."/includes/places/addFormFields.inc");
	?>
<fieldset><legend>Submit</legend>
	<button type="submit" class="submit">Submit</button>
	<button type="button" class="done" onclick="self.close();">Done</button>
</fieldset>
</form>