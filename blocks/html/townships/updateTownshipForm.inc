<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Township $this->township
 */
?>
<h1>Update TownshipMaster</h1>
<form method="post" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>">
	<fieldset><legend>Township Info</legend>
		<input name="township_id" type="hidden" value="<?php echo $this->township->getTownship_id(); ?>" />
		<table>

			<tr><td><label for="township-name" class="required">Name</label></td>
				<td><input name="township[name]" id="township-name" value="<?php echo View::escape($this->township->getName()); ?>" />
				</td>
			</tr>

			<tr><td><label for="township-township_abbreviation" class="required">Abbreviation</label></td>
				<td><input name="township[township_abbreviation]" id="township-township_abbreviation" value="<?php echo View::escape($this->township->getTownship_abbreviation()); ?>" />
				</td>
			</tr>

			<tr><td><label for="township-quarter_code">Quarter Code</label></td>
				<td><input name="township[quarter_code]" id="township-quarter_code" value="<?php echo View::escape($this->township->getQuarter_code()); ?>" />
				</td>
			</tr>

		</table>

		<button type="submit" class="submit">Submit</button>
		<button type="button" class="cancel" onclick="document.location.href='<?php echo BASE_URL; ?>/townships';">
			Cancel
		</button>
	</fieldset>
</form>
