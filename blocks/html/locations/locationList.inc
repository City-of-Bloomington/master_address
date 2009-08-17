<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param LocationList $this->locationList
 */
?>
<h2>Locations</h2>
<table>
	<tr><th>LID</th>
		<th>Location ID</th>
		<th>Type</th>
		<th>Address</th>
		<th>Name</th>
		<th>Active</th>
		<th>Mailable</th>
		<th>Livable</th>
	</tr>
	<?php
		foreach ($this->locationList as $location) {
			$name = View::escape($location->getCommon_name());
			$active = $location->isActive() ? 'y' : 'n';
			$mailable = $location->isMailable() ? 'y' : 'n';
			$livable = $location->isLivable() ? 'y' : 'n';
			echo "
			<tr><td>{$location->getLid()}</td>
				<td>{$location->getLocation_id()}</td>
				<td>{$location->getType()}</td>
				<td>{$location->getAddress()->getStreetAddress()}</td>
				<td>$name</td>
				<td>$active</td>
				<td>$mailable</td>
				<td>$livable</td>
			</tr>
			";
		}
	?>
</table>