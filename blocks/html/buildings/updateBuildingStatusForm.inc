<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
?>
<h1>Update Building Status</h1>
<form method="post" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>">
	<fieldset><legend>Building Status Info</legend>
		<input name="status_code" type="hidden" value="<?php echo $this->buildingStatus->getStatus_code(); ?>" />
		<table>

			<tr><td><label for="buildingStatus-description">description</label></td>
				<td><input name="buildingsStatus[description]" id="buildingStatus-description" value="<?php echo $this->buildingStatus->getDescription(); ?>" />
				</td>
			</tr>
				
		</table>

		<button type="submit" class="submit">Submit</button>
		<button type="button" class="cancel" onclick="document.location.href='<?php echo BASE_URL; ?>/buildings';">
			Cancel
		</button>
	</fieldset>
</form>