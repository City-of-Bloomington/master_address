<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @author W. Sibo <sibow@bloomington.in.gov>
 * @param Street $this->street
 */
?>
<h1>Update Street</h1>
<form method="post" action="<?php echo BASE_URL; ?>/streets/actions.php"
	onsubmit="FRAMEWORK.checkAndgetChangeLog(this,'correctForm'); return false;">
	<fieldset><legend>Street Info</legend>
		<input name="street_id" type="hidden" value="<?php echo $this->street->getStreet_id(); ?>" />
		<input name="action" type="hidden" value="correct" />
		<table>
			<tr><td><label for="street-town_id">Town</label></td>
				<td><select name="street[town_id]" id="street-town_id">
						<option></option>
						<?php
							$towns = new TownList();
							$towns->find();
							foreach ($towns as $town) {
								$selected = ($this->street->getTown_id() == $town->getId())
											? 'selected="selected"'
											: '';
								$description = View::escape($town->getDescription());
								echo "
								<option $selected value=\"{$town->getId()}\">
									$description
								</option>
								";
							}
						?>
					</select>
				</td>
			</tr>

			<tr><td><label for="street-notes">Notes</label></td>
				<td><input name="street[notes]" id="street-notes"
							value="<?php echo View::escape($this->street->getNotes()); ?>" />
				</td>
			</tr>

		</table>

		<button type="submit" class="submit">Save Changes</button>
	</fieldset>

	<?php
		$block = new Block('changeLogs/changeLogEntryFields.inc',
							array('action'=>'correct','id'=>'correctForm'));
		echo $block->render('html');
	?>
</form>
