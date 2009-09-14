<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @author W. Sibo <sibow@bloomington.in.gov>
 * @param Address $this->address
 * @param Location $this->location
 */
?>
<h1>Add Address</h1>

<form method="post" action="<?php echo BASE_URL; ?>/addresses/addAddress.php">
	<table>
	<tr><td><fieldset>
				<table>
					<tr><td>Action</td>
						<td><label>
								<input type="radio" name="action" value="add" />
								Add
							</label>
							<label>
								<input type="radio" name="action" value="assign" checked="checked" />
								Assign
							</label>
						</td>
					</tr>
					<tr><td><label>Street</label></td>
						<td><?php
								$block = new Block('addresses/partials/streetSelector.inc',
													array('street'=>$this->address->getStreet()));
								echo $block->render('html');
							?>
						</td>
					</tr>
					<tr><td><label for="street_number">Number</label></td>
						<td><input name="street_number" id="street_number" /></td>
					</tr>
				</table>
			</fieldset>

			<fieldset>
				<?php include APPLICATION_HOME.'/blocks/html/addresses/partials/addressFields.inc'; ?>
			</fieldset>
		</td>
		<td><fieldset>
				<input type="hidden" name="location_id" value="<?php echo $this->location->getId(); ?>" />
				<?php include APPLICATION_HOME.'/blocks/html/addresses/partials/locationFields.inc'; ?>
			</fieldset>
		</td>
	</tr>
	</table>
	<fieldset>
		<button type="submit" class="submit">Save</button>
		<label>
			<input type="checkbox" name="batch_mode" />
			Submit in Batch Mode
		</label>
	</fieldset>
</form>
