<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Town $this->town
 */
?>
<h1>Update Town</h1>
<form method="post" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>">
	<fieldset><legend>Town Info</legend>
		<input name="town_id" type="hidden" value="<?php echo $this->town->getTown_id(); ?>" />
		<table>

			<tr><td><label for="town-description" class="required">Description</label></td>
				<td><input name="town[description]" id="town-description" value="<?php echo View::escape($this->town->getDescription()); ?>" />
				</td>
			</tr>

			<tr><td><label for="town-town_code" class="required">Code</label></td>
				<td><input name="town[town_code]" id="town-town_code" value="<?php echo View::escape($this->town->getTown_code()); ?>" />
				</td>
			</tr>

		</table>

		<button type="submit" class="submit">Submit</button>
		<button type="button" class="cancel" onclick="document.location.href='<?php echo BASE_URL; ?>/towns';">
			Cancel
		</button>
	</fieldset>
</form>
