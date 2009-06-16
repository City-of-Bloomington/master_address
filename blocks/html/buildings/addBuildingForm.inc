<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
?>
<h1>Add Building</h1>
<form method="post" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>">
	<fieldset><legend>Building Info</legend>
		<table>
			<tr><td><label for="building-building_type_id" class="required">Type</label></td>
				<td><select name="building[building_type_id]" id="building-building_type_id">
						<?php
							$types = new BuildingTypeList();
							$types->find();
							foreach ($types as $type) {
								$name = View::escape($type->getDescription());
								echo "<option value=\"{$type->getId()}\">$name</option>";
							}
						?>
					</select>
				</td>
			</tr>

			<tr><td><label for="building-gis_tag">Gis Tag</label></td>
				<td><input name="building[gis_tag]" id="building-gis_tag" />
				</td>
			</tr>
			<tr><td><label for="building-building_name">Name</label></td>
				<td><input name="building[building_name]" id="building-building_name" />
				</td>
			</tr>

			<tr><td><label for="building-effective_start_date">Start Date</label></td>
				<td><input name="building[effective_start_date]" id="building-efffective_start_date" />
					(m/d/Y)
				</td>
			</tr>
			<tr><td><label for="building-effective_end_date">End Date</label></td>
				<td><input name="building[effective_end_date]" id="building-effective_end_date" />
					(m/d/Y)
				</td>
			</tr>
			<tr><td><label for="building-status_code">Status</label></td>
				<td><select name="building[status_code]" id="building-status_code">
					<?php
						$statuses = new BuildingStatusList();
						$statuses->find();
						foreach ($statuses as $status) {
							$name = View::escape($status->getDescription());
							echo "<option value=\"{$status->getId()}\">$name</option>";
						}
					?>
					</select>
				</td>

			</tr>

		</table>

		<button type="submit" class="submit">Submit</button>
		<button type="button" class="cancel" onclick="document.location.href='<?php echo BASE_URL; ?>/buildings';">
			Cancel
		</button>
	</fieldset>
</form>