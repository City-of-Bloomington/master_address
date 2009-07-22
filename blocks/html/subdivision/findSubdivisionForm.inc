<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
$sub = new SubdivisionName();
$statuses = $sub->getStatuses();
?>

<h1>Find Subdivisions</h1>
<form method="get" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>">
	<fieldset><legend>Subdivision Info</legend>
		<table>
			<tr><td><label for="sub-township_id">Township</label></td>
				<td><select name="sub[township_id]" id="sub-township_id">
                        <option></option>
                        <?php
							$townships = new TownshipList();
							$townships->find();
                            foreach ($townships as $township) {
								$selected = (isset($_GET['sub']['township_id'])
												&& $_GET['sub']['township_id'] == $township->getId())
										? 'selected=\"selected\"'
										: '';

								echo "
								<option $selected value=\"{$township->getId()}\">
									{$township->getName()}
								</option>
								";
			            }
		            ?>
                   </select>
				</td>
			</tr>
			<tr><td><label for="sub-name">Name</label></td>
				<td><input name="sub[name]" id="sub-name" value="<?php if (isset($_GET['sub']['name'])) echo View::escape($_GET['sub']['name']); ?>" />
				</td>
			</tr>
   			<tr><td><label for="sub-phase">Phase</label></td>
				<td><input name="sub[phase]" id="sub-phase" value="<?php if (isset($_GET['sub']['phase'])) echo View::escape($_GET['sub']['phase']); ?>" />
				</td>
			</tr>
			<tr><td><label for="sub-status">Status</label></td>
				<td><select name="sub[status]" id="sub-status">
						<option></option>
						<?php
							foreach ($statuses as $status) {
								$selected = (isset($_GET['sub']['status'])
												&& $_GET['sub']['status']==$status)
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
