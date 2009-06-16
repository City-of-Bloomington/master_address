<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
?>
<div class="interfaceBox">
	<h1>
		<?php
			if (userHasRole('Administrator')) {
				echo "<a class=\"add button\" href=\"".BASE_URL."/addressChange/addAddressLocationChange.php\">Add</a>";
			}
		?>
		Address Location Change
	</h1>
	    <table>
		<tr><th></th>
			<th>Location ID</th>
			<th>Old Location ID</th>
			<th>Change date</th>
		    <th>Notes</th>
		</tr>
	<?php
			foreach ($this->addressLocationChangeList as $change) {
			    $editButton = '';
				if (userHasRole('Administrator')) {
					$url = new URL(BASE_URL.'/addressChange/updateAddressLocationChange.php');
					$url->location_change_id = $change->getLocation_change_id();
					$editButton = "<a class=\"edit button\" href=\"$url\">Edit</a>";
				}
				echo
				  "<tr>
				    <td>$editButton</td>
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