<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
?>
<h1>Add Street Name Type</h1>
<form method="post" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>">
	<fieldset><legend>Street Name Type Info</legend>
		<table>

			<tr><td><label for="streetNameType-street_name_type">Street Name Type</label></td>
				<td><input name="streetNameType[street_name_type]" id="streetNameType-street_name_type" />
				</td>
			</tr>
				
			<tr><td><label for="streetNameType-description">Description</label></td>
				<td><input name="streetNameType[description]" id="streetNameType-description" />
				</td>
			</tr>
				
		</table>

		<button type="submit" class="submit">Submit</button>
		<button type="button" class="cancel" onclick="document.location.href='<?php echo BASE_URL; ?>/streets';">
			Cancel
		</button>
	</fieldset>
</form>