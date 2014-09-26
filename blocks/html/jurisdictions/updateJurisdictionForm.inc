<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Jurisdiction $this->jurisdiction
 */
?>
<h1>Update Government Jurisdiction</h1>
<form method="post" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>">
	<fieldset><legend>Government Jurisdiction Info</legend>
		<input name="jurisdiction_id" type="hidden" value="<?php echo $this->jurisdiction->getId(); ?>" />
		<table>

			<tr><td><label for="jurisdiction-description" class="required">Description</label></td>
				<td><input name="jurisdiction[description]" id="jurisdiction-description" value="<?php echo View::escape($this->jurisdiction->getDescription()); ?>" />
				</td>
			</tr>

		</table>

		<button type="submit" class="submit">Submit</button>
		<button type="button" class="cancel" onclick="document.location.href='<?php echo BASE_URL; ?>/jurisdictions';">
			Cancel
		</button>
	</fieldset>
</form>
