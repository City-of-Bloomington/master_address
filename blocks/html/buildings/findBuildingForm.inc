<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
?>
<h1>Find a Building</h1>
<form method="get" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>">
	<fieldset><legend>Building Info</legend>
		<table>

			<tr><td><label for="building-building_name">Name</label></td>
				<td><input name="building[building_name]" id="building-building_name" value="<?php if (isset($_GET['building']['building_name'])) echo View::escape($_GET['building']['building_name']); ?>" />
				</td>
			</tr>
   			<tr><td><label for="building-gis_tag">GIS Tag</label></td>
				<td><input name="building[gis_tag]" id="building-gis_tag" value="<?php if (isset($_GET['building']['gis_tag'])) echo View::escape($_GET['building']['gis_tag']); ?>" />
				</td>
			</tr>

			<tr><td><label for="building-building_type_id">Building Type</label></td>
				<td><select name="building[building_type_id]" id="building-building_type_id">
						<option></option>
						<?php
							$list = new BuildingTypeList();
							$list->find();
							foreach ($list as $type) {
								$name = View::escape($type->getDescription());
								$selected = (isset($_GET['building']['building_type_id'])
											 && $_GET['building']['building_type_id']==$type->getId())
											? 'selected="selected"'
											: '';
								echo "<option value=\"{$type->getId()}\" $selected>$name</option>";
							}
						?>
					</select>
				</td>
			</tr>
			<tr><td><label for="building-status_code">Building Status</label></td>
				<td><select name="building[status_code]" id="building-status_code">
						<option></option>
						<?php
							$list = new BuildingStatusList();
							$list->find();
							foreach ($list as $status) {
								$name = View::escape($status->getDescription());
								$selected = (isset($_GET['building']['status_code'])
											 && $_GET['building']['status_code']==$status->getId())
											? 'selected="selected"'
											: '';
								echo "<option value=\"{$status->getId()}\" $selected>$name</option>";
							}
						?>
					</select>
				</td>
			</tr>

		</table>

		<button type="submit" class="search">Search</button>
	</fieldset>
</form>
