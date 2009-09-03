<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @author W. Sibo <sibow@bloomington.in.gov>
 * @param string $this->action
 */
?>
<form method="post" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>">
	<fieldset><legend>Street Name</legend>
	<?php include APPLICATION_HOME.'/blocks/html/streets/partials/addStreetNameFields.inc'; ?>
	</fieldset>

	<fieldset><legend>Street Information</legend>
		<input name="action" type="hidden" value="<?php echo View::escape($this->action); ?>" />
		<table>
			<tr><td><label for="street-town_id">Town</label></td>
				<td><select name="street[town_id]" id="street-town_id" >
						<option></option>
						<?php
							$towns = new TownList();
							$towns->find();
							foreach ($towns as $town) {
								$description = View::escape($town->getDescription());
								echo "<option value=\"{$town->getId()}\">$description</option>";
							}
						?>
					</select>
				</td>
			</tr>

			<tr><td><label for="street-notes">Notes</label></td>
				<td><textarea name="street[notes]" id="street-notes" rows="4" cols="30" wrap="wrap">
				</textarea>
				</td>
			</tr>

		</table>

		<button type="submit" class="submit">Submit Street</button>
	</fieldset>

	<fieldset id="changeLogEntry_fields">
		<legend>Change Log Entry</legend>
		<?php
			$block = new Block('changeLogs/changeLogEntryFields.inc',
								array('action'=>$this->action));
			echo $block->render('html');
		?>
	</fieldset>
</form>
