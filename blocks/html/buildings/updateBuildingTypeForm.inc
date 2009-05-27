<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param BuildingType $this->buildingType
 */
?>
<h1>Update Building Type</h1>
<form method="post" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>">
	<fieldset><legend>Building Type Info</legend>
		<input name="buildingType_id" type="hidden" value="<?php echo $this->buildingType->getId(); ?>" />
		<table>

			<tr><td><label for="buildingType-description" class="required">Description</label></td>
				<td><input name="buildingType[description]" id="buildingType-description" value="<?php echo View::escape($this->buildingType->getDescription()); ?>" />
				</td>
			</tr>

		</table>

		<button type="submit" class="submit">Submit</button>
		<button type="button" class="cancel" onclick="document.location.href='<?php echo BASE_URL; ?>/buildings';">
			Cancel
		</button>
	</fieldset>
</form>
