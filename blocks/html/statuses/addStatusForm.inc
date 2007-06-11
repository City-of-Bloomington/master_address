<?php
/*
	$this->type
*/
?>
<h1>Add a new <?php echo $this->type; ?> status code</h1>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<fieldset><legend><?php echo ucfirst($this->type); ?> Status</legend>
	<input name="type" type="hidden" value="<?php echo $this->type; ?>" />

	<table>
	<tr><td><label for="status-status">Status</label></td>
		<td><input name="status[status]" id="status-status" value="<?php echo $this->status->getStatus(); ?>" /></td></tr>
	</table>

	<button type="submit" class="submit">Submit</button>
	<button type="button" class="cancel" onclick="document.location.href='<?php echo BASE_URL; ?>/statuses';">Cancel</button>
</fieldset>
</form>