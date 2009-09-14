<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Address $this->address
 */
// Set up all the valid actions based on the current status of the address
$actions = array('correct','readdress','verify');
if ($this->address->getStatus()->getDescription() == 'RETIRED') {
	$actions[] = 'unretire';
	$actions[] = 'reassign';
}
else {
	$actions[] = 'retire';
}

echo "<h1>Address Information</h1>";
if (userIsAllowed('Address')) {
	echo "
	<table>
		<tr><th>Action</th>
			<td><select onchange=\"handleActionChoice(this);\">
					<option value=\"\">Action to Apply</option>
	";
					foreach ($actions as $action) {
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