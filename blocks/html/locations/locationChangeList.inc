<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param LocationChangeList $this->locationChangeList
 */
?>
<div class="interfaceBox">
	<h1>
		<?php
			if (userIsAllowed('LocationChange')) {
				echo "<a class=\"add button\" href=\"".BASE_URL."/locations/addLocationChange.php\">Add</a>";
			}
		?>
		Location Change
	</h1>
    <table>
		<tr><th></th>
			<th>Location ID</th>
			<th>Old Location ID</th>
			<th>Change date</th>
		    <th>Notes</th>
		</tr>
		<?php
			foreach ($this->locationChangeList as $change) {
				$editButton = '';
				if (userIsAllowed('LocationChange')) {
					$url = new URL(BASE_URL.'/locations/updateLocationChange.php');
					$url->location_change_id = $change->getLocation_change_id();
					$editButton = "<a class=\"edit button\" href=\"$url\">Edit</a>";
				}
				echo "
				<tr><td>$editButton</td>
					<td>{$change->getLocation_id()}</td>
					<td>{$change->getOld_location_id()}</td>
					<td>{$change->getChange_date('n/j/Y')}</td>
					<td>{$change->getNotes()}</td>
					</tr>
				";
			}
		?>
	</table>
</div>