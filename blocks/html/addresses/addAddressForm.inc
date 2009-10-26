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
<table>
<tbody>
	<tr>
		<td><label for="action" class="required">Action</label></td>
		<td  style="float:left;">
			<label>
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
<?php include APPLICATION_HOME.'/blocks/html/addresses/partials/streetFields.inc';
				?>
<?php include APPLICATION_HOME.'/blocks/html/addresses/partials/addressMappingFieldsrequired.inc';
?>
				</tbody>
	</table>
	