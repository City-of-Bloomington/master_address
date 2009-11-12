<div class="interfaceBox">
<h1>Address Information</h1>
<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Address $this->address
 */
// Set up all the valid actions based on the current status of the address
$status = strtolower($this->address->getStatus()->getDescription());
$actions = array('correct','update','readdress','verify');
if ($status == 'retired') {
	$actions[] = 'unretire';
	$actions[] = 'reassign';
}
else {
	$actions[] = 'retire';
}

if (userIsAllowed('Address')) {
	echo "
	<table>
		<tr><th>Action</th>
			<td><select onchange=\"handleActionChoice(this);\">
					<option value=\"\">Action to Apply</option>
	";
					foreach ($actions as $action) {
						echo "<option value=\"$action\">$action</option>";
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
echo "<table class=\"$status\">";
	include APPLICATION_HOME.'/blocks/html/addresses/partials/streetData.inc';
	include APPLICATION_HOME.'/blocks/html/addresses/partials/requiredData.inc';
	include APPLICATION_HOME.'/blocks/html/addresses/partials/unrequiredData.inc';
	include APPLICATION_HOME.'/blocks/html/locations/partials/locationData.inc';
	include APPLICATION_HOME.'/blocks/html/addresses/partials/sanitationData.inc';
echo "</table>";
include APPLICATION_HOME.'/blocks/html/addresses/partials/notes.inc';
?>
</div>