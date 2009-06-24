<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Address $this->address
 */
echo "
<h1>{$this->address->getStreetAddress()}</h1>
<table>
	<tr><th>Address</th>
		<td>{$this->address->getStreetAddress()},
			{$this->address->getCity()}, {$this->address->getState()}
			{$this->address->getZip()}
		</td>
	</tr>
	<tr><th>Coordinates</th>
		<td>{$this->address->getLatitude()},{$this->address->getLongitude()}</td>
	</tr>
	<tr><th>State Plane</th>
		<td>{$this->address->getState_plane_x_coordinate()},
			{$this->address->getState_plane_y_coordinate()}
		</td>
	</tr>
	<tr><th>Jurisdiction</th>
		<td>{$this->address->getJurisdiction()->getDescription()}</td>
	</tr>
	<tr><th>Township</th>
		<td>{$this->address->getTownship()->getName()}</td>
	</tr>
	<tr><th>Council District</th>
		<td>{$this->address->getCityCouncilDistrict()}</td>
	</tr>
	<tr><th>Trash Day</th>
		<td>{$this->address->getTrashDay()}</td>
	</tr>
	<tr><th>Recycle Week</th>
		<td>{$this->address->getRecycleWeek()}</td>
	</tr>
</table>
";
	$purposes = $this->address->getLocation()->getPurposes();
	if (count($purposes)) {
		echo "
		<h2>Purposes</h2>
		<table>
		";
		foreach ($purposes as $purpose) {
			echo "
			<tr><th>{$purpose->getType()}</th>
				<td>{$purpose->getDescription()}</td>
			</tr>
			";
		}
		echo "</table>";
	}
echo "
<h2>Locations</h2>
<table>
	<tr><th>Location_id</th>
	</tr>
";
$locations = $this->address->getLocations();
foreach ($locations as $location) {
	echo "
	<tr><td>{$location->getLocation_id()}</td>
	</tr>
	";
}
echo "
</table>
";