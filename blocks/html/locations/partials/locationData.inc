<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Address $this->address
 */
$active = $this->address->isActive()? 'Yes' : 'No';
$location = $this->address->getLocation();
$locationType = $location ? $location->getLocationType($this->address) : '';
echo "
	<tr><th>Location Use Type</th>
		<td>$locationType</td>
	</tr>
	<tr><th>Mailable</th>
		<td>{$this->address->getMailable()}</td>
	</tr>
	<tr><th>Livable</th>
		<td>{$this->address->getLivable()}</td>
	</tr>
	<tr><th>Active</th>
		<td>$active</td>
	</tr>
";