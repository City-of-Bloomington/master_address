<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Address $this->address
 */
// Set up all the valid actions based on the current status of the address
$actions = array('correct'=>"Correct something in the primary attributes of the address, but it isn't a change of address.",
				'update'=>'Update any other address attributes that is not Re-Addressing',
				'readdress'=>'Change of address, involves change in the Street and/or Street Number.',
				'verify'=>'Confirm the address information is correct');
if ($this->address->getStatus()->getDescription() == 'RETIRED') {
	$actions['unretire'] = 'Unretire the address. Subunits will remain retired.';
	$actions['reassign'] = 'Use this address at a new location';
}
else {
	$actions['retire'] = 'Retires the address.  All subunits will be retired as well.';
}

echo "<h1>Address Information</h1>";
if (userIsAllowed('Address')) {
	echo "
	<table>
	";
	foreach ($actions as $action=>$description) {
		$action = ucfirst($action);
		echo "
		<tr><th>$action</th>
			<td>$description</td>
		</tr>
		";
	}
	echo "
	</table>
	<table>
		<tr><th>Action</th>
			<td><select onchange=\"handleActionChoice(this);\">
					<option value=\"\">Action to Apply</option>
	";
					foreach (array_keys($actions) as $action) {
						echo "<option>$action</option>";
					}
	echo "
				</select>
			</td>
		</tr>
	</table>
	<script type=\"text/javascript\">
		function handleActionChoice(select) {
			document.location.href = '".BASE_URL."/addresses/actions.php?action=' + select.options[select.selectedIndex].value + ';address_id={$this->address->getId()}';
		}
	</script>
	";
}

include APPLICATION_HOME.'/blocks/html/addresses/partials/streetData.inc';
include APPLICATION_HOME.'/blocks/html/addresses/partials/addressData.inc';
include APPLICATION_HOME.'/blocks/html/addresses/partials/addressMappingData.inc';