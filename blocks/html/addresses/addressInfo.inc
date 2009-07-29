<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Address $this->address
 */
$township = $this->address->getTownship()
			? View::escape($this->address->getTownship()->getName())
			: '';
$action = '';
if (userIsAllowed('Address')) {
	$action = "
		<tr><th>Action</th>
			<td><select>
					<option>change</option>
					<option>correct</option>
					<option>update</option>
					<option>verify</option>
					<option>retire</option>
					<option>unretire</option>
				</select>
			</td>
		</tr>
	";
}
echo "
<h1>Address Information</h1>
<table>
	$action
	<tr><th>Street</th>
		<td>{$this->address->getStreet()->getStreetName()}</td>
	</tr>
	<tr><th>Number</th>
		<td>{$this->address->getStreet_number()}</td>
	</tr>
	<tr><th>Status</th>
		<td>{$this->address->getStatus()}</td>
	</tr>
	<tr><th>Type</th>
		<td>{$this->address->getType()}</td>
	</tr>
	<tr><th>Zip Code</th>
		<td>{$this->address->getZip()}-{$this->address->getZipplus4()}</td>
	</tr>
	<tr><th>Trash Day</th>
		<td>{$this->address->getTrashDay()}</td>
	</tr>
	<tr><th>Recycle Week</th>
		<td>{$this->address->getRecycleWeek()}</td>
	</tr>
	<tr><th>Jurisdiction</th>
		<td>{$this->address->getJurisdiction()->getDescription()}</td>
	</tr>
	<tr><th>Township</th>
		<td>$township</td>
	</tr>
	<tr><th>Section</th>
		<td>{$this->address->getSection()}
			Quarter Section
			{$this->address->getQuarter_section()}
		</td>
	</tr>
	<tr><th>Census Block</th>
		<td>{$this->address->getCensus_block_fips_code()}</td>
	</tr>

	<tr><th colspan=\"2\">Coordinates</th></tr>
	<tr><th>Lat/Long</th>
		<td>{$this->address->getLatitude()},{$this->address->getLongitude()}</td>
	</tr>
	<tr><th>State Plane X/Y</th>
		<td>{$this->address->getState_plane_x_coordinate()},
			{$this->address->getState_plane_y_coordinate()}
		</td>
	</tr>
</table>
";
