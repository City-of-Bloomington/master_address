<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
$types = new SubunitTypeList();
$types->find();

?>

<h1>Add Subunit</h1>
<form method="post" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>">
	<fieldset><legend>Subunit Information</legend>
		<table>

			<tr><td><label>Address</label></td>
                <td><?php echo $this->address->getId(); ?>
                    <input name="street_address_id" type="hidden" value="<?php echo $this->address->getId() ?>" />
				</td>
			</tr>
				
			<tr><td><label for="subunit-sudtype">Type</label></td>
				<td><select name="subunit[sudtype]" id="subunit-sudtype">
                         <option></option>
                     <?php
                          foreach($types as $type){
	                          echo "<option value=\"{$type->getSudtype()}\">{$type->getDescription()}</option>";
                          }
                     ?>
                   </select>
				</td>
			</tr>
				
			<tr><td><label for="subunit-street_subunit_identifier">Unit</label></td>
				<td><input name="subunit[street_subunit_identifier]" id="subunit-street_subunit_identifier" />
				</td>
			</tr>
			<tr><td>&nbsp;</td>
				<td>Comma separated for multiple subunts (Ex. A, B, C)
				</td>
			</tr>
			
			<tr><td><label for="subunit-notes">notes</label></td>
				<td><input name="subunit[notes]" id="subunit-notes" />
				</td>
			</tr>
			<tr><td colspan="2">
				<fieldset>
				<?php include APPLICATION_HOME.'/blocks/html/addresses/partials/locationFields.inc'; ?>
				</fieldset>
				</td>
			</tr>
		</table>

		<button type="submit" class="submit">Submit</button>
        <input type="checkbox" name="batch_mode" value="y" />Submit in Batch Mode

	</fieldset>
</form>