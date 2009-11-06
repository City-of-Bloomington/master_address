<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Address $this->address
 */
echo "
	<tr><th>State Plane X/Y</th>
		<td>{$this->address->getState_plane_x_coordinate()},
			{$this->address->getState_plane_y_coordinate()}
		</td>
	</tr>
	<tr><th>Lat/Long</th>
		<td>{$this->address->getLatitude()},{$this->address->getLongitude()}</td>
	</tr>
        <tr><th>Census Block</th>
		<td>{$this->address->getCensus_block_fips_code()}</td>
	</tr>
	<tr><th>Tax Jurisdiction</th>
		<td>{$this->address->getTax_jurisdiction()}</td>
	</tr>
";