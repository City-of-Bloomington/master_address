<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
$townships = new TownshipList();
$townships->find();
?>
<h1>Add Subdivision</h1>
<form method="post" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>">
	<fieldset><legend>Subdivision Info</legend>
		<table>

			<tr><td><label for="subdivision-township_id">Township</label></td>
				<td><select name="subdivision[township_id]" id="subdivision-township_id" >
                    <option></option>
                    <?php
                         foreach ($townships as $township){
				               echo "<option value=\"{$township->getId()}\">
                                    {$township->getName()}</option>";
			            }
		            ?>
                   </select>
				</td>
			</tr>
				
		</table>

		<button type="submit" class="submit">Submit</button>
		<button type="button" class="cancel" onclick="document.location.href='<?php echo BASE_URL; ?>/subdivisions';">
			Cancel
		</button>
	</fieldset>
</form>