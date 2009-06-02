<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
?>
<h1>Add Address Location Change</h1>
<form method="post" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>">
	<fieldset><legend>Address Location Change Info</legend>
		<table>

			<tr><td><label for="addressLocationChange-location_id">Location ID</label></td>
				<td><input name="addressLocationChange[location_id]" id="addressLocationChange-location_id" />
				</td>
			</tr>
				
			<tr><td><label for="addressLocationChange-old_location_id">Old Location ID</label></td>
				<td><input name="addressLocationChange[old_location_id]" id="addressLocationChange-old_location_id" />
				</td>
			</tr>
				
			<tr><td><label for="addressLocationChange-change_date">Change Date</label></td>
				<td><input name="addressLocationChange[change_date]" id="addressLocationChange-change_date" />
				</td>
			</tr>
			<tr><td><label for="addressLocationChange-notes">notes</label></td>
				<td><input name="addressLocationChange[notes]" id="addressLocationChange-notes" />
				</td>
			</tr>
				
		</table>

		<button type="submit" class="submit">Submit</button>
		<button type="button" class="cancel" onclick="document.location.href='<?php echo BASE_URL; ?>/addressChange';">
			Cancel
		</button>
	</fieldset>
</form>