<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Address $this->address
 */
?>
<table>
	<tr><td><label for="streetName" class="required">Street</label></td>
		<td><?php
				$block = new Block('addresses/partials/streetSelector.inc',
									array('street'=>$this->address->getStreet()));
				echo $block->render();
			?>
		</td>
	</tr>
	<tr><td><label for="street_number" class="required">Number</label></td>
		<td><input name="street_number" id="street_number"
					value="<?php echo View::escape($this->address->getStreet_number()); ?>" />
		</td>
	</tr>
	<tr><td><label>Status</label></td>
		<td><?php echo $this->address->getStatus(); ?></td>
	</tr>
</table>
