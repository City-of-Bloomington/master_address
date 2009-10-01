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
$plat = $this->address->getPlat()
		? View::escape($this->address->getPlat()->getName())
		: '';
$notes = View::escape($this->address->getNotes());
echo "
<table>
	<tr><th>Zip Plus4</th>
		<td>{$this->address->getZipplus4()}</td>
	</tr>
	<tr><th>Plat</th>
		<td>$plat</td>
	</tr>
	<tr><th>Lot Number</th>
		<td>{$this->address->getPlat_lot_number()}</td>
	</tr>
	<tr><th>Jurisdiction</th>
		<td>{$this->address->getJurisdiction()->getDescription()}</td>
	</tr>
	<tr><th>Township</th>
		<td>$township</td>
	</tr>
	<tr><th>Section</th>
		<td>{$this->address->getSection()}</td>
	</tr>
	<tr><th>Quarter Section</th>
		<td>{$this->address->getQuarter_section()}</td>
	</tr>
	<tr><th>Census Block</th>
		<td>{$this->address->getCensus_block_fips_code()}</td>
	</tr>
	<tr><th>Lat/Long</th>
		<td>{$this->address->getLatitude()},{$this->address->getLongitude()}</td>
	</tr>
	<tr><th>State Plane X/Y</th>
		<td>{$this->address->getState_plane_x_coordinate()},
			{$this->address->getState_plane_y_coordinate()}
		</td>
	</tr>
</table>
<p>$notes</p>
";