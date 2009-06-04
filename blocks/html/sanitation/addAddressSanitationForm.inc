<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
?>
<h1>Add Address Sanitation</h1>
<form method="post" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>">
	<fieldset><legend>Address Sanitation Info</legend>
   	    <input name="street_address_id" type="hidden" value="<?php echo $this->street_address_id; ?>" />
		<table>

			<tr><td><label for="address_sanitation-trash_pickup_day">Trash Pickup Day</label></td>
				<td><input name="addressSanitation[trash_pickup_day]" id="address_sanitation-trash_pickup_day" />
				</td>
			</tr>
				
			<tr><td><label for="address_sanitation-recycle_week">Recycle Week</label></td>
				<td><input name="addressSanitation[recycle_week]" id="address_sanitation-recycle_week" />
				</td>
			</tr>
				
		</table>

		<button type="submit" class="submit">Submit</button>
		<button type="button" class="cancel" onclick="document.location.href='<?php echo BASE_URL; ?>/sanitations';">
			Cancel
		</button>
	</fieldset>
</form>