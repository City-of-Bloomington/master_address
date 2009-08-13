<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Address $this->address
 */
?>
<table>
	<tr><td><label>Street</label></td>
		<td><?php echo $this->address->getStreet()->getStreetName(); ?></td>
	</tr>
	<tr><td><label for="address-street_number" class="required">Number</label></td>
		<td><input name="address[street_number]" id="address-street_number"
					value="<?php echo View::escape($this->address->getStreet_number()); ?>" />
		</td>
	</tr>
	<tr><td><label>Status</label></td>
		<td><?php echo $this->address->getStatus(); ?></td>
	</tr>
</table>
