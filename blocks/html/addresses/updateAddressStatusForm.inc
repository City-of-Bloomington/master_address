<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param AddressStatus $this->addressStatus
 */
?>
<h1>Update Address Status</h1>
<form method="post" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>">
	<fieldset><legend>Address Status Info</legend>
		<input name="status_code" type="hidden" value="<?php echo $this->addressStatus->getCode(); ?>" />
		<table>

			<tr><td><label for="addressStatus-description" class="required">Description</label></td>
				<td><input name="addressStatus[description]" id="addressStatus-description" value="<?php echo View::escape($this->addressStatus->getDescription()); ?>" />
				</td>
			</tr>

		</table>

		<button type="submit" class="submit">Submit</button>
		<button type="button" class="cancel" onclick="document.location.href='<?php echo BASE_URL; ?>/addresses';">
			Cancel
		</button>
	</fieldset>
</form>
