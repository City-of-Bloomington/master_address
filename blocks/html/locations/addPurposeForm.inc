<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
?>
<h1>Add Purpose</h1>
<form method="post" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>">
	<fieldset><legend>Purpose Info</legend>
		<table>

			<tr><td><label for="purpose-description" class="required">Description</label></td>
				<td><input name="purpose[description]" id="purpose-description" />
				</td>
			</tr>

			<tr><td><label for="purpose-type" class="required">Type</label></td>
				<td><input name="purpose[type]" id="purpose-type" />
				</td>
			</tr>

		</table>

		<button type="submit" class="submit">Submit</button>
		<button type="button" class="cancel" onclick="document.location.href='<?php echo BASE_URL; ?>/locations';">
			Cancel
		</button>
	</fieldset>
</form>
