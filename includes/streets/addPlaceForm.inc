<?php
/*
	Required Variables:		street
							segment
*/
?>
<form method="post" action="<?php $_SERVER['PHP_SELF']; ?>">
	<?php
		include(APPLICATION_HOME."/includes/places/addFormFields.inc");
		include(APPLICATION_HOME."/includes/addresses/addFormFields.inc");
	?>
<fieldset><legend>Submit</legend>
	<button type="submit" class="submit">Submit</button>
</fieldset>
</form>