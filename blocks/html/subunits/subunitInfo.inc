<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @author W Sibo <sibow@bloomington.in.gov>
 * @param Subunit $this->subunit
 */
echo "<h1>Subunit Information</h1>";
if (userIsAllowed('Address')) {
	echo "
	<table>
		<tr><th>Action</th>
			<td><select onchange=\"handleActionChoice(this);\">
					<option value=\"\">Action to Apply</option>
					<option value=\"correct\">Correct</option>
					<option value=\"verify\">Verify</option>
					<option value=\"retire\">Retire</option>
				</select>
			</td>
		</tr>
	</table>
	<script type=\"text/javascript\">
		function handleActionChoice(select) {
			document.location.href = '".BASE_URL."/subunits/actions.php?action=' + select.options[select.selectedIndex].value + ';subunit_id={$this->subunit->getId()}';
		}
	</script>
	";
}
include APPLICATION_HOME.'/blocks/html/subunits/partials/subunitData.inc';