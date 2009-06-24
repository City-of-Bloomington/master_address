<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Address $this->address
 */
echo "
<h1>{$this->address->getStreetAddress()}</h1>
<h2>Primary Location: {$this->address->getLocation()->getLocation_id()}</h2>
<h2>Council District: {$this->address->getCityCouncilDistrict()}</h2>
<h2>Purposes</h2>
<table>
	<tr><th>Purpose</th>
	</tr>
";
	foreach ($this->address->getLocation()->getPurposes() as $purpose) {
		echo "
		<tr><td>{$purpose->getDescription()}</td>
		</tr>
		";
	}
echo "
</table>
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