<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Purpose $this->purpose
 */
?>
<h1>Update Purpose</h1>
<form method="post" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>">
	<fieldset><legend>Purpose Info</legend>
		<input name="purpose_id" type="hidden" value="<?php echo $this->purpose->getId(); ?>" />
		<table>

			<tr><td><label for="purpose-description">Description</label></td>
				<td><input name="purpose[description]" id="purpose-description" value="<?php echo View::escape($this->purpose->getDescription()); ?>" />
				</td>
			</tr>

			<tr><td><label for="purpose-type">Type</label></td>
				<td><input name="purpose[type]" id="purpose-type" value="<?php echo View::escape($this->purpose->getType()); ?>" />
				</td>
			</tr>

		</table>

		<button type="submit" class="submit">Submit</button>
		<button type="button" class="cancel" onclick="document.location.href='<?php echo BASE_URL; ?>/locations';">
			Cancel
		</button>
	</fieldset>
</form>
