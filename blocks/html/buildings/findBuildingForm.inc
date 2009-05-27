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

			<tr><td><label for="plat-name">Name</label></td>
				<td><input name="plat[name]" id="plat-name" value="<?php if (isset($_GET['plat']['name'])) echo View::escape($_GET['plat']['name']); ?>" />
				</td>
			</tr>

			<tr><td><label for="plat-township_id">Township</label></td>
				<td><select name="plat[township_id]" id="plat-township_id">
						<option></option>
						<?php
							$list = new TownshipList();
							$list->find();
							foreach ($list as $township) {
								$name = View::escape($township->getName());
								$selected = (isset($_GET['plat']['township_id'])
											 && $_GET['plat']['township_id']==$township->getId())
											? 'selected="selected"'
											: '';
								echo "<option value=\"{$township->getId()}\" $selected>$name</option>";
							}
						?>
					</select>
				</td>
			</tr>

			<tr><td><label for="plat-plat_type">Type</label></td>
				<td><select name="plat[plat_type]" id="plat-plat_type">
						<option></option>
						<?php
							foreach (Plat::getPlat_types() as $type) {
								$selected = (isset($_GET['plat']['plat_type'])
											 && $_GET['plat']['plat_type']==$type)
											? 'selected="selected"'
											: '';
								echo "<option $selected>$type</option>";
							}
						?>
					</select>
				</td>
			</tr>

			<tr><td><label for="plat-plat_cabinet">Cabinet</label></td>
				<td><input name="plat[plat_cabinet]" id="plat-plat_cabinet" value="<?php if (isset($_GET['plat']['plat_cabinet'])) echo View::escape($_GET['plat']['plat_cabinet']); ?>" />
				</td>
			</tr>

			<tr><td><label for="plat-envelope">Envelope</label></td>
				<td><input name="plat[envelope]" id="plat-envelope" value="<?php if (isset($_GET['plat']['envelope'])) echo View::escape($_GET['plat']['envelope']); ?>" />
				</td>
			</tr>

		</table>

		<button type="submit" class="search">Search</button>
	</fieldset>
</form>
