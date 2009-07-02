<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
?>
<h1>Update Location Change</h1>
<form method="post" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>">
	<fieldset><legend>Location Change Info</legend>
		<input name="location_change_id" type="hidden" value="<?php echo $this->locationChange->getLocation_change_id(); ?>" />
		<table>

			<tr><td><label for="locationChange-location_id">Location ID</label></td>
				<td><input name="locationChange[location_id]" id="locationChange-location_id" value="<?php echo $this->locationChange->getLocation_id(); ?>" />
				</td>
			</tr>
				
			<tr><td><label for="locationChange-old_location_id">Old Location ID</label></td>
				<td><input name="locationChange[old_location_id]" id="locationChange-old_location_id" value="<?php echo $this->locationChange->getOld_location_id(); ?>" />
				</td>
			</tr>
				
			<tr><td><label for="locationChange-change_date">Change Date</label></td>
                <td><input name="locationChange[change_date]" id="locationChange-change_date" size="10" maxlength="10" value="<?php echo $this->locationChange->getChange_date('n/j/Y'); ?>" />
				</td>
			</tr>
			<tr><td><label for="locationChange-notes">Notes</label></td>
				<td><input name="locationChange[notes]" id="locationChange-notes" value="<?php echo View::escape($this->locationChange->getNotes()); ?>" />
				</td>
			</tr>
				
		</table>

		<button type="submit" class="submit">Submit</button>
		<button type="button" class="cancel" onclick="document.location.href='<?php echo BASE_URL; ?>/locations';">
			Cancel
		</button>
	</fieldset>
</form>