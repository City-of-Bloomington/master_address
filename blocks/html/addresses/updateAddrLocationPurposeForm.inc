<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
?>
<h1>Update Address Location Purpose</h1>
<form method="post" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>">
	<fieldset><legend>Address Location Purpose Info</legend>
		<input name="location_purpose_id" type="hidden" value="<?php echo $this->addrLocationPurpose->getLocation_purpose_id(); ?>" />
		<table>

			<tr><td><label for="addr_location_purpose-description">Description</label></td>
				<td><input name="addrLocationPurpose[description]" id="addr_location_purpose-description" value="<?php echo View::escape($this->addrLocationPurpose->getDescription()); ?>" />
				</td>
			</tr>
				
			<tr><td><label for="addr_location_purpose-type">Type</label></td>
				<td><input name="addrLocationPurpose[type]" id="addr_location_purpose-type" value="<?php echo View::escape($this->addrLocationPurpose->getType()); ?>" />
				</td>
			</tr>
				
		</table>

		<button type="submit" class="submit">Submit</button>
		<button type="button" class="cancel" onclick="document.location.href='<?php echo BASE_URL; ?>/addresses';">
			Cancel
		</button>
	</fieldset>
</form>