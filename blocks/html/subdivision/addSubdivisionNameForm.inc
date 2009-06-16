<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
$subs = new SubdivsionList();
$subs.find();
$sub = new Subdivision();
$statuses = $sub.getStatuses();
?>
<h1>Add Subdivision Name</h1>
<form method="post" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>">
	<fieldset><legend>Subdivision Name Info</legend>
		<table>
			<tr><td><label for="subdivisioName-subdivision_id">Subdivision ID</label></td>
				<td><select name="subdivisioName[subdivision_id]" id="subdivisioName-subdivision_id" >
                    <option></option>
                    <?php
                         foreach ($subs as $subdivision){
				               echo "<option value='{$subdivision->getId()}'>
                                    {$subdivision->getTownship()->getName()}</option>";
			            }
		            ?>
                   </select>
				</td>
			</tr>
				
			<tr><td><label for="subdivisioName-name">Name</label></td>
				<td><input name="subdivisioName[name]" id="subdivisioName-name" />
				</td>
			</tr>
				
			<tr><td><label for="subdivisioName-phase">Phase</label></td>
				<td><input name="subdivisioName[phase]" id="subdivisioName-phase" />
				</td>
			</tr>
				
			<tr><td><label for="subdivisioName-status">Status</label></td>
				<td><select name="subdivisioName[status]" id="subdivisioName-status" >
                    <option></option>
                    <?php
                         foreach ($statuses as $status){
				             echo "<option>$status</option>";
			            }
		            ?>
                   </select>
				</td>
			</tr>
				
			<tr><td><label for="subdivisioName-effective_start_date">Effective Start Date</label></td>
				<td><input name="subdivisioName[effective_start_date]" id="subdivisioName-effective_start_date" size="10" maxlength="10" />
				</td>
			</tr>
			<tr><td><label for="subdivisioName-effective_end_date">Effective End Date</label></td>
				<td><input name="subdivisioName[effective_end_date]" id="subdivisioName-effective_end_date" size="10" maxlength="10" />
				</td>
			</tr>
		</table>

		<button type="submit" class="submit">Submit</button>
		<button type="button" class="cancel" onclick="document.location.href='<?php echo BASE_URL; ?>/subdivisions';">
			Cancel
		</button>
	</fieldset>
</form>