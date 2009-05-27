<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Suffix $this->suffix
 */
?>
<h1>Update Suffix</h1>
<form method="post" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>">
	<fieldset><legend>Suffix Info</legend>
		<input name="suffix_code" type="hidden" value="<?php echo $this->suffix->getCode(); ?>" />
		<table>

			<tr><td><label for="suffix-description" class="required">Description</label></td>
				<td><input name="suffix[description]" id="suffix-description" value="<?php echo View::escape($this->suffix->getDescription()); ?>" />
				</td>
			</tr>

		</table>

		<button type="submit" class="submit">Submit</button>
		<button type="button" class="cancel" onclick="document.location.href='<?php echo BASE_URL; ?>/streets';">
			Cancel
		</button>
	</fieldset>
</form>
