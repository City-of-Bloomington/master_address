<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param StreetType $this->streetType
 */
?>
<h1>Update Street Type</h1>
<form method="post" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>">
	<fieldset><legend>Street Type Info</legend>
		<input name="streetType_id" type="hidden" value="<?php echo $this->streetType->getId(); ?>" />
		<table>
			<tr><td><label for="streetType-streetType_code" class="required">Code</label></td>
				<td><input name="streetType[streetType_code]" id="streetType-streetType_code" value="<?php echo View::escape($this->streetType->getCode()); ?>" />
				</td>
			</tr>
			<tr><td><label for="streetType-description" class="required">Description</label></td>
				<td><input name="streetType[description]" id="streetType-description" value="<?php echo View::escape($this->streetType->getDescription()); ?>" />
				</td>
			</tr>

		</table>

		<button type="submit" class="submit">Submit</button>
		<button type="button" class="cancel" onclick="document.location.href='<?php echo BASE_URL; ?>/streets';">
			Cancel
		</button>
	</fieldset>
</form>
