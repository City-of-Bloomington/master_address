<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @author W. Sibo <sibow@bloomington.in.gov>
 * @param Address $this->address
 * @param Location $this->location
 * @param array $this->locationData
 */
$this->address->setStreet_number('');
?>
<h1>Add Address</h1>
<script type="text/javascript">
var addAddressFormAction = 'assign';
function handleAddAddress(form) {
	if (FRAMEWORK.checkRequiredFields()) {
		FRAMEWORK.getChangeLog(form,addAddressFormAction);
	}
}
</script>
<form method="post" action="<?php echo BASE_URL; ?>/addresses/addAddress.php"
	onsubmit="handleAddAddress(this);return false;">
	<table>
		<tr><td><table>
					<tr><td><label for="action_add" class="required">Action</label></td>
						<td><label style="float:none;">
								<input type="radio" name="action" id="action_add" value="add"
									onclick="addAddressFormAction='add';" />
								Add
							</label>
							<label style="float:none;">
								<input type="radio" name="action" id="action_assign" value="assign" checked="checked"
									onclick="addAddressFormAction='assign';" />
								Assign
							</label>
						</td>
					</tr>
					<?php
						include APPLICATION_HOME.'/blocks/html/addresses/partials/streetFields.inc';
						include APPLICATION_HOME.'/blocks/html/addresses/partials/requiredFields.inc';
					?>
				</table>
			</td>
			<td><table>
					<?php
						include APPLICATION_HOME.'/blocks/html/addresses/partials/unrequiredFields.inc';
						include APPLICATION_HOME.'/blocks/html/locations/partials/locationFields.inc';
						include APPLICATION_HOME.'/blocks/html/addresses/partials/sanitationFields.inc';
					?>
					<tr><td><label for="notes">Notes</label></td>
						<td><textarea name="notes" id="notes" rows="3" cols="50"><?php echo View::escape($this->address->getNotes()); ?></textarea>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<div><button type="submit" class="submit">Save</button>
		<label>
			<input type="checkbox" name="batch_mode" />
				Submit in Batch Mode
		</label>
	</div>
</form>
