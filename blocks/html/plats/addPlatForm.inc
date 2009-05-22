<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
?>
<h1>Add Plat</h1>
<form method="post" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>">
	<fieldset><legend>Plat Info</legend>
		<table>

			<tr><td><label for="plat-name" class="required">Name</label></td>
				<td><input name="plat[name]" id="plat-name" />
				</td>
			</tr>

			<tr><td><label for="plat-township_id" class="required">Township</label></td>
				<td><select name="plat[township_id]" id="plat-township_id">
						<?php
							$townships = new TownshipList();
							$townships->find();
							foreach ($townships as $township) {
								$name = View::escape($township->getName());
								echo "<option value=\"{$township->getId()}\">$name</option>";
							}
						?>
					</select>
				</td>
			</tr>

			<tr><td><label for="plat-effective_start_date">Effective Start Date</label></td>
				<td><input name="plat[effective_start_date]" id="plat-effective_start_date" size="11" maxlength="10" />
				</td>
			</tr>
			<tr><td><label for="plat-effective_end_date-mon">Effective End Date</label></td>
				<td><input name="plat[effective_end_date]" id="plat-effective_end_date" size="11" maxlength="10" />
				</td>
			</tr>
			<tr><td><label for="plat-plat_type" class="required">Type</label></td>
				<td><select name="plat[plat_type]" id="plat-plat_type">
					<?php
						foreach (Plat::getPlat_types() as $type) {
							echo "<option>$type</option>";
						}
					?>
					</select>
				</td>
			</tr>

			<tr><td><label for="plat-plat_cabinet">Cabinet</label></td>
				<td><input name="plat[plat_cabinet]" id="plat-plat_cabinet" />
				</td>
			</tr>

			<tr><td><label for="plat-envelope">Envelope</label></td>
				<td><input name="plat[envelope]" id="plat-envelope" />
				</td>
			</tr>

			<tr><td><label for="plat-notes">Notes</label></td>
				<td><textarea name="plat[notes]" id="plat-notes" rows="3" cols="50"></textarea>
				</td>
			</tr>

		</table>

		<button type="submit" class="submit">Submit</button>
		<button type="button" class="cancel" onclick="document.location.href='<?php echo BASE_URL; ?>/plats';">
			Cancel
		</button>
	</fieldset>
</form>
