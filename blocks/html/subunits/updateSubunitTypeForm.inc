<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param SubunitType $this->subunitType
 */
?>
<h1>Update Subunit Type</h1>
<form method="post" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>">
	<fieldset><legend>Subunit Type Info</legend>
		<input name="subunitType_id" type="hidden" value="<?php echo $this->subunitType->getId(); ?>" />
		<table>

			<tr><td><label for="subunitType-sudtype" class="required">Type</label></td>
				<td><input name="subunitType[sudtype]" id="subunitType-sudtype" value="<?php echo View::escape($this->subunitType->getSudtype()); ?>" />
				</td>
			</tr>

			<tr><td><label for="subunitType-description" class="required">Description</label></td>
				<td><input name="subunitType[description]" id="subunitType-description" value="<?php echo View::escape($this->subunitType->getDescription()); ?>" />
				</td>
			</tr>

		</table>

		<button type="submit" class="submit">Submit</button>
		<button type="button" class="cancel" onclick="document.location.href='<?php echo BASE_URL; ?>/subunits';">
			Cancel
		</button>
	</fieldset>
</form>