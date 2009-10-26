<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Address $this->address
 */
?>
<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
?>
	
	<tr><td><label for="jurisdiction_id" class="required">Jurisdiction</label></td>
		<td><select name="jurisdiction_id" id="jurisdiction_id">
			<?php
				$list = new JurisdictionList();
				$list->find();
				foreach ($list as $jurisdiction) {
					$name = View::escape($jurisdiction);
					$selected = $this->address->getJurisdiction_id() == $jurisdiction->getId()
						? 'selected="selected"'
						: '';
					echo "<option value=\"{$jurisdiction->getId()}\" $selected>$name</option>";
				}
			?>
			</select>
		</td>
	</tr>
	<tr><td><label for="plat_id">Plat</label></td>
		<td><?php
				$block = new Block('addresses/partials/platSelector.inc',
									array('plat'=>$this->address->getPlat()));
				echo $block->render('html');
			?>
		</td>
	</tr>
	<tr><td><label for="plat_lot_number">Lot Number</label></td>
		<td><input name="plat_lot_number" id="plat_lot_number" size="4"
				value="<?php echo $this->address->getPlat_lot_number(); ?>" />
		</td>
	</tr>
	
	<tr><td><label for="township_id" class="required">Township</label></td>
		<td><select name="township_id" id="township_id" >
				<option></option>
				<?php
					$list = new TownshipList();
					$list->find();
					foreach ($list as $township) {
						$name = View::escape($township->getName());
						$selected = $this->address->getTownship_id() == $township->getId()
							? 'selected="selected"'
							: '';
						echo "<option $selected value=\"{$township->getId()}\">$name</option>";
					}
				?>
			</select>
		</td>
	</tr>
	<tr><td><label for="section">Section</label></td>
		<td><select name="section" id="section">
				<option></option>
				<?php
					foreach (Address::getSections() as $section) {
						$selected = $this->address->getSection()==$section
									? 'selected="selected"'
									: '';
						echo "<option $selected>$section</option>";
					}
				?>
			</select>
			<label for="quarter_section">Quarter Section</label>
			<select name="quarter_section" id="quarter_section">
				<option></option>
				<?php
					foreach (Address::getQuarterSections() as $quarter) {
						$selected = $this->address->getQuarter_section()==$quarter
									? 'selected="selected"'
									: '';
						echo "<option $selected>$quarter</option>";
					}
				?>
			</select>
		</td>
	</tr>