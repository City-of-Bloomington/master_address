<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Address $this->address
 */

?>
<table>
	<tr><td><label for="address_type" class="required">Address Type</label></td>
		<td><select name="address_type" id="address_type">
			<?php
				foreach (Address::getAddressTypes() as $type) {
					$selected = $this->address->getAddress_type() == $type
						? 'selected="selected"'
						: '';
					echo "<option $selected>$type</option>";
				}
			?>
			</select>
		</td>
	</tr>

	<tr><td><label for="trash_pickup_day">Trash Schedule</label></td>
		<td><select name="trash_pickup_day" id="trash_pickup_day">
				<option></option>
				<?php
					foreach (Address::getTrashDays() as $day) {
						$selected = $this->address->getTrash_pickup_day()==$day
							? 'selected="selected"'
							: '';
						echo "<option $selected>$day</option>";
					}
				?>
			</select>
		</td>
	</tr>
</table>
