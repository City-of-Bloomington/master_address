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
?>
<h1>Add Address</h1>
<script type="text/javascript">
var addAddressFormAction = 'add';
function handleAddAddress(form) {
	if (FRAMEWORK.checkRequiredFields()) {
		FRAMEWORK.getChangeLog(form,addAddressFormAction);
	}
}
</script>
<form method="post" action="<?php echo BASE_URL; ?>/addresses/addAddress.php"
	onsubmit="handleAddAddress(this); return false;">
	<table>
	<tr><td><fieldset>
				<table class="action">
					<tr><td>Action</td>
						<td><label>
								<input type="radio" name="action" value="add"
									onclick="addAddressFormAction='add';" />
								Add
							</label>
							<label>
								<input type="radio" name="action" value="assign" checked="checked"
									onclick="addAddressFormAction='assign';" />
								Assign
							</label>
						</td>
					</tr>
				</table>
			</fieldset>
			<fieldset>
				<?php
					include APPLICATION_HOME.'/blocks/html/addresses/partials/streetFields.inc';
				?>
			</fieldset>

			<fieldset>
				<?php
					include APPLICATION_HOME.'/blocks/html/addresses/partials/addressFields.inc';
					if (isset($this->location)) {
						echo "<input type=\"hidden\" name=\"location_id\" value=\"{$this->location->getId()}\" />";
						$this->locationData = $this->location->getUpdatableData($this->address);
					}
					include APPLICATION_HOME.'/blocks/html/locations/partials/locationFields.inc';
				?>
			</fieldset>
		</td>
		<td><fieldset>
				<?php
					include APPLICATION_HOME.'/blocks/html/addresses/partials/addressMappingFields.inc';
				?>
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
