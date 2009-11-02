<form method="post" action="<?php echo BASE_URL; ?>/addresses/addAddress.php"
	onsubmit="handleAddAddress(this); return false;">
	<table>
	<tbody>
<?php include APPLICATION_HOME.'/blocks/html/addresses/partials/addressMappingFieldsunrequired.inc'; ?>
	
	<?php include APPLICATION_HOME.'/blocks/html/locations/partials/locationFields.inc';

					if (isset($this->location)) {
						echo "<input type=\"hidden\" name=\"location_id\" value=\"{$this						->location->getId()}\" />";
						$this->locationData = $this->location->getUpdatableData($this->address);
					}
	 ?>
			
		

			
	
<tr>
	<td><label for="notes">Notes</label></td>
	<td><textarea name="notes" id="notes" rows="3" cols="50"><?php echo View::escape($this->address->getNotes()); ?></textarea></td>
</tr>
	<fieldset>
	<td>	<button type="submit" class="submit">Save</button></td>
	<td>	<label style="float:right;">
			<input type="checkbox" name="batch_mode" />
			Submit in Batch Mode
		</label>
	</td>
</tr>
	</fieldset>
</form>
</tbody>
	</table>

	
