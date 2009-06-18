<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
$subs = new SubdivisionList();
$subs->find();
$sub = new SubdivisionName();
$statuses = $sub->getStatuses();
?>
<h1>Update Subdivision Name</h1>
<form method="post" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>">
	<fieldset><legend>Subdivision Name Info</legend>
		<input name="subdivision_name_id" type="hidden" value="<?php echo $this->subdivisionName->getSubdivision_name_id(); ?>" />
  		<input name="subdivisionName[subdivision_id]" type="hidden" value="<?php echo $this->subdivisionName->getSubdivision_id(); ?>" />
		<table>
			<tr><td><label for="subdivisionName-name">Name</label></td>
				<td><input name="subdivisionName[name]" id="subdivisionName-name" value="<?php echo $this->subdivisionName->getName(); ?>" />
				</td>
			</tr>
				
			<tr><td><label for="subdivisionName-phase">Phase</label></td>
				<td><input name="subdivisionName[phase]" id="subdivisionName-phase" value="<?php echo $this->subdivisionName->getPhase(); ?>" />
				</td>
			</tr>
				
			<tr><td><label for="subdivisionName-status">Status</label></td>
				<td><select name="subdivisionName[status]" id="subdivisionName-status" >
                      <option></option>
                    <?php
                         foreach ($statuses as $status){
		                     $selected=($this->subdivisionName->getStatus() == $status)?"selected=\"selected\"":"";	
				             echo "<option $selected>$status</option>";
			            }
		            ?>
                   </select>
				</td>
			</tr>
				
			<tr><td><label for="subdivisionName-effective_start_date">Effective Start Date</label></td>
				<td><input name="subdivisionName[effective_start_date]" id="subdivisionName-effective_start_date" size="10" maxlength="10" value="<?php echo $this->subdivisionName->getEffective_start_date('n/j/Y'); ?>" />
  
				</td>
			</tr>
			<tr><td><label for="subdivisionName-effective_end_date-mon">effective_end_date</label></td>
				<td><input name="subdivisionName[effective_end_date]" id="subdivisionName-effective_end_date" size="10" maxlength="10" value="<?php echo $this->subdivisionName->getEffective_end_date('n/j/Y'); ?>" />
  
				</td>
			</tr>
		</table>

		<button type="submit" class="submit">Submit</button>
		<button type="button" class="cancel" onclick="document.location.href='<?php echo BASE_URL; ?>/subdivisions';">
			Cancel
		</button>
	</fieldset>
</form>