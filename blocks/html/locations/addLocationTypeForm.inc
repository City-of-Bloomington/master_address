<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
?>
<h1>Add LocationType</h1>
<form method="post" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>">
	<fieldset><legend>LocationType Info</legend>
		<table>

			<tr><td><label for="locationType-location_type_id" class="required">Type</label></td>
				<td><input name="locationType[location_type_id]" id="locationType-location_type_id" />
				</td>
			</tr>

			<tr><td><label for="locationType-description" class="required">Description</label></td>
				<td><input name="locationType[description]" id="locationType-description" />
				</td>
			</tr>

		</table>

		<button type="submit" class="submit">Submit</button>
		<button type="button" class="cancel" onclick="document.location.href='<?php echo BASE_URL; ?>/locations';">
			Cancel
		</button>
	</fieldset>
</form>