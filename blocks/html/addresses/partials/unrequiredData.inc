<?php
/**
 * @copyright 2009-2014 City of Bloomington, Indiana
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
		<td>{$this->address->getLatitude()}, {$this->address->getLongitude()}</td>
	</tr>
	<tr><th>USNG</th>
		<td>{$this->address->getUsng_coordinate()}</td>
	</tr>
";
