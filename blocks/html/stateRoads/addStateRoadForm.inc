<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
?>
<h1>Add State Road</h1>
<form method="post" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>">
	<fieldset><legend>State Road Info</legend>
		<table>

			<tr><td><label for="stateRoad-description" class="required">Description</label></td>
				<td><input name="stateRoad[description]" id="stateRoad-description" />
				</td>
			</tr>

			<tr><td><label for="stateRoad-abbreviation" class="required">Abbreviation</label></td>
				<td><input name="stateRoad[abbreviation]" id="stateRoad-abbreviation" />
				</td>
			</tr>

		</table>

		<button type="submit" class="submit">Submit</button>
		<button type="button" class="cancel" onclick="document.location.href='<?php echo BASE_URL; ?>/stateRoads';">
			Cancel
		</button>
	</fieldset>
</form>
