<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
?>
<h1>Update Street Name Type</h1>
<form method="post" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>">
	<fieldset><legend>Street Name Type Info</legend>
		<input name="street_name_type" type="hidden" value="<?php echo $this->streetNameType->getStreet_name_type(); ?>" />
		<table>
            <tr>
                <td><label>Name Type</label></td>
                <td><?php echo $this->streetNameType->getStreet_name_type() ?></td>
            </tr>
            <tr>
                <td><label for="street_name_type-description">Description</label></td>

				<td><input name="streetNameType[description]" id="street_name_type-description" value="<?php echo $this->streetNameType->getDescription(); ?>" />
				</td>
			</tr>
				
		</table>

		<button type="submit" class="submit">Submit</button>
		<button type="button" class="cancel" onclick="document.location.href='<?php echo BASE_URL; ?>/streets';">
			Cancel
		</button>
	</fieldset>
</form>