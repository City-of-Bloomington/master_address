<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @author W. Sibo <sibow@bloomington.in.gov>
 * @param Street $this->street
 * @param Address $this->address
 * @param Location $this->location
 */
$street = $this->street;
$address = $this->address;
$location = $this->location;
?>

<h1>Add Address</h1>

<div id="addAddressForm" class="yui-skin-sam">
	<form method="post" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>">
		<fieldset><legend>Address Information</legend>
			<table>
				<tr><td>
						<table> <!-- left table -->
							<tr><td><label>Action</label></td>
								<td><input type="radio" name="action" value="add" checked="checked" />Add<input type="radio" name="action" value="assign" />Assign</td></tr>
							<tr><td><label>Street</label></td>
								<td><?php include APPLICATION_HOME.'/blocks/html/addresses/partials/streetSelector.inc'; ?>
								</td>
							</tr>
							<tr><td><label for="address-street_number">Number</label></td>
								<td><input name="address[street_number]" id="address-street_number" />
								</td>
							</tr>
						</table>
						<?php include APPLICATION_HOME.'/blocks/html/addresses/partials/addressFields.inc'; ?>
					</td>
					<td>
						<table> <!-- right table -->

							<tr><td><label>Location ID</label></td>
								<td><input type="hidden" name="lid" value="<?php echo $location->getLid(); ?>" /> <?php echo $location->getLid(); ?>
								</td>
							</tr>

							<tr><td><label for="location-mailable">Mailable</label></td>
								<td><?php
										$checked = $location->isMailable() ? 'checked="checked"' : '';
										echo "<input type=\"checkbox\" name=\"location[mailable_flag]\" value=\"y\" id=\"location-mailable\" $checked />";
									?>
								</td>
							</tr>

							<tr><td><label for="location-livable">Livable</label></td>
								<td><?php
										$checked = $location->isLivable() ? 'checked="checked"' : '';
										echo "<input type=\"checkbox\" name=\"location[livable_flag]\" value=\"y\" id=\"location-livable\" $checked />";
									?>
								</td>
							</tr>

							<tr><td><label for="location-active">Current Address</label></td>
								<td><?php
										$checked = $location->isActive() ? 'checked="checked"' : '';
										echo "<input type=\"checkbox\" name=\"location[active]\" value=\"y\" id=\"location-active\" $checked />";
									?>
								</td>
							</tr>

							<tr><td><label for="location-type">Location Type</label></td>
								<td><select name="location[location_type_id]">
										<?php
											$ltypes = new LocationTypeList();
											$ltypes->find();
											foreach ($ltypes as $type) {
												$selected = $location->getLocation_type_id()==$type->getId()
															? $selected = 'selected="selected"'
															: '';
												echo "<option $selected >{$type->getId()}</option>";
											}
										?>
									</select>
								</td>
							</tr>
							<tr><td colspan="2">
									<label for="address-notes">Notes</label>
									<div><textarea name="address[notes]" id="address-notes" rows="5" cols="50"><?php echo View::escape($address->getNotes()); ?></textarea>
									</div>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>

			<button type="submit" class="submit">Save</button>
			<input type="checkbox" name="batch_mode" id="batch_mode" value="y" />
			<label for="batch_mode" />Submit in Batch Mode</label>

		</fieldset>
	</form>
</div>
