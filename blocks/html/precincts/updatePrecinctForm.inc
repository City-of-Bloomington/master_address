<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Precinct $this->precinct
 */
?>
<h1>Update Precinct</h1>
<form method="post" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>">
	<fieldset><legend>Precinct Info</legend>
		<input name="code" type="hidden" value="<?php echo $this->precinct->getCode(); ?>" />
		<table>

			<tr><td><label for="precinct-precinct_name" class="required">Name</label></td>
				<td><input name="precinct[precinct_name]" id="precinct-precinct_name" value="<?php echo View::escape($this->precinct->getName()); ?>" />
				</td>
			</tr>

			<tr><td><label for="precinct-active" class="required">Active</label></td>
				<td><select name="precinct[active]" id="precinct-active">
						<option <?php echo $this->precinct->isActive() ? 'selected="selected"' : ''; ?>>Y</option>
						<option <?php echo !$this->precinct->isActive() ? 'selected="selected"' : ''; ?>>N</option>
					</select>
				</td>
			</tr>

		</table>

		<button type="submit" class="submit">Submit</button>
		<button type="button" class="cancel" onclick="document.location.href='<?php echo BASE_URL; ?>/precincts';">
			Cancel
		</button>
	</fieldset>
</form>
