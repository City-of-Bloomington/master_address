<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Address $this->address
 */
echo "<h1>Address Information</h1>";
if (userIsAllowed('Address')) {
	echo "
	<table>
		<tr><th>Action</th>
			<td><select onchange=\"handleActionChoice(this);\">
					<option value=\"\">Action to Apply</option>
					<option>correct</option>
					<option>change</option>
					<option>verify</option>
					<option>retire</option>
					<option>unretire</option>
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