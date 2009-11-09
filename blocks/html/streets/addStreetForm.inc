<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @author W. Sibo <sibow@bloomington.in.gov>
 */
?>
<script type="text/javascript">
var addStreetFormAction = 'create';
function handleAddStreet(form) {
	if (FRAMEWORK.checkRequiredFields()) {
		FRAMEWORK.getChangeLog(form,addStreetFormAction,'<?php echo BASE_URL; ?>');
	}
}
</script>
<form method="post" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>"
	onsubmit="handleAddStreet(this); return false;">
	<fieldset>
		<label>
			<input type="radio" name="action" value="add"
				onclick="addStreetFormAction='add';" />
			Add
		</label>
		<label>
			<input type="radio" name="action" value="create" checked="checked"
				onclick="addStreetFormAction='create';" />
			Create
		</label>
		<label>
			<input type="radio" name="action" value="propose"
				onclick="addStreetFormAction='propose';" />
			Propose
		</label>
	</fieldset>

	<fieldset><legend>Street Name</legend>
	<?php include APPLICATION_HOME.'/blocks/html/streets/partials/streetNameFields.inc'; ?>
	</fieldset>

	<fieldset><legend>Street Information</legend>
		<table>
			<tr><td><label for="town_id">Town</label></td>
				<td><select name="town_id" id="town_id" >
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

			<tr><td><label for="notes">Notes</label></td>
				<td><textarea name="notes" id="notes" rows="4" cols="30" wrap="wrap"></textarea>
				</td>
			</tr>

		</table>

		<button type="submit" class="submit">Submit Street</button>
	</fieldset>
</form>
