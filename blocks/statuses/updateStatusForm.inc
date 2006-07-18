<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<fieldset><legend>Status</legend>
	<input name="id" type="hidden" value="<?php echo $this->status->getId(); ?>" />

	<table>
	<tr><td><label for="status-status">Status</label></td>
		<td><input name="status[status]" id="status-status" value="<?php echo $this->status->getStatus(); ?>" /></td></tr>
	</table>

	<button type="submit" class="submit">Submit</button>
	<button type="button" class="cancel" onclick="document.location.href='<?php echo BASE_URL; ?>/statuses';">Cancel</button>
</fieldset>
</form>