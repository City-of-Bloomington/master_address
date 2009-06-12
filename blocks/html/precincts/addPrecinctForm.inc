<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
?>
<h1>Add Precinct</h1>
<form method="post" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>">
	<fieldset><legend>Precinct Info</legend>
		<table>

			<tr><td><label for="precinct-precinct" class="required">Code</label></td>
				<td><input name="precinct[precinct]" id="precinct-precinct" />
				</td>
			</tr>

			<tr><td><label for="precinct-precinct_name" class="required">Name</label></td>
				<td><input name="precinct[precinct_name]" id="precinct-precinct_name" />
				</td>
			</tr>

			<tr><td><label for="precinct-active-yes">Active</label></td>
				<td><label>
						<input name="precinct[active]" id="precinct-active-yes"
								type="radio" value="Y" checked="checked" />
						Yes
					</label>
					<label>
						<input name="precinct[active]" id="precinct-active-no"
								type="radio" value="N" />
						No
					</label>
				</td>
			</tr>

		</table>

		<button type="submit" class="submit">Submit</button>
		<button type="button" class="cancel" onclick="document.location.href='<?php echo BASE_URL; ?>/precincts';">
			Cancel
		</button>
	</fieldset>
</form>