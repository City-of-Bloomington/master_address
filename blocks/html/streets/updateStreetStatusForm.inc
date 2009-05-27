<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param StreetStatus $this->streetStatus
 */
?>
<h1>Update Street Status</h1>
<form method="post" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>">
	<fieldset><legend>Street Status Info</legend>
		<input name="status_code" type="hidden" value="<?php echo $this->streetStatus->getStatus_code(); ?>" />
		<table>

			<tr><td><label for="streetStatus-description" class="required">Description</label></td>
				<td><input name="streetStatus[description]" id="streetStatus-description" value="<?php echo View::escape($this->streetStatus->getDescription()); ?>" />
				</td>
			</tr>

		</table>

		<button type="submit" class="submit">Submit</button>
		<button type="button" class="cancel" onclick="document.location.href='<?php echo BASE_URL; ?>/streets';">
			Cancel
		</button>
	</fieldset>
</form>
