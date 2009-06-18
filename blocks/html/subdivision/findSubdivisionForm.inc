<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
$sub = new SubdivisionName();
$statuses = $sub->getStatuses();
$townships = new TownshipList();
$townships->find();
?>
<h1>Find Subdivisions</h1>
<form method="get" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>">
	<fieldset><legend>Subdivision Info</legend>
		<table>
        <!--
			<tr><td><label for="subdivisionName-township_id">Township</label></td>
				<td><select name="subdivisionName[township_id]" id="subdivisionName-township_id">
                        <option></option>
                        <?php
  						 	$selected = (isset($_GET['subdivisionName']['township_id'])
								   		 && $_GET['subdivisionName']['township_id']==$township->getId())
											? 'selected="selected"'
											: '';
                            foreach ($townships as $township){
				               echo "<option $selected value=\"{$township->getId()}\">
                                    {$township->getName()}</option>";
			            }
		            ?>
                   </select>
				</td>
			</tr>
				   -->
			<tr><td><label for="subdivisionName-name">Name</label></td>
				<td><input name="subdivisionName[name]" id="subdivisionName-name" value="<?php if (isset($_GET['subdivisionName']['name'])) echo View::escape($_GET['subdivisionName']['name']); ?>" />
				</td>
			</tr>
   			<tr><td><label for="subdivisionName-phase">Phase</label></td>
				<td><input name="subdivisionName[phase]" id="subdivisionName-phase" value="<?php if (isset($_GET['subdivisionName']['phase'])) echo View::escape($_GET['subdivisionName']['phase']); ?>" />
				</td>
			</tr>
			<tr><td><label for="subdivisionName-status">Status</label></td>
				<td><select name="subdivisionName[status]" id="subdivisionName-status">
						<option></option>
						<?php
                           foreach ($statuses as $status){
								$selected = (isset($_GET['subdivisionName']['status'])
											 && $_GET['subdivisionName']['status']==$status)
											? 'selected="selected"'
											: '';
								echo "<option $selected>$status</option>";
							}
						?>
					</select>
				</td>
			</tr>

		</table>

		<button type="submit" class="search">Search</button>
	</fieldset>
</form>
