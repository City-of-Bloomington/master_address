<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
?>
<h1>Update Address Location Type</h1>
<form method="post" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>">
	<fieldset><legend>Address Location Type Info</legend>
		<input name="location_type_id" type="hidden" value="<?php echo $this->addrLocationType->getLocation_type_id(); ?>" />
		<table>

			<tr><td><label for="addrLocationType-description">Description</label></td>
				<td><input name="addrLocationType[description]" id="addrLocationType-description" value="<?php echo $this->addrLocationType->getDescription(); ?>" />
				</td>
			</tr>
				
		</table>

		<button type="submit" class="submit">Submit</button>
		<button type="button" class="cancel" onclick="document.location.href='<?php echo BASE_URL; ?>/addresses';">
			Cancel
		</button>
	</fieldset>
</form>