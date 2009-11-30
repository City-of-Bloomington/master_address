<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Subunit $this->subunit
 */
$location = $this->subunit->getLocation();
$mailable = '';
$livable = '';
$active = '';
$type = '';
if ($location) {
	$mailable = $location->getMailable($this->subunit);
	$livable = $location->getLivable($this->subunit);
	$active = $location->isActive($this->subunit)? 'Yes' : 'No';
	$type = $location->getLocationType($this->subunit);
}
echo "
	<tr><th>SUD Type</th>
		<td>{$this->subunit->getSudtype()}</td>
	</tr>
	<tr><th>#</th>
		<td>{$this->subunit->getIdentifier()}</td>
	</tr>
	<tr><th>Status</th>
		<td>{$this->subunit->getStatus()}</td>
	</tr>
	<tr><th>Location Use Type</th>
	    <td>$type</td>
	</tr>
	<tr><th>Mailable</th>
		<td>$mailable</td>
	</tr>
	<tr><th>Livable</th>
		<td>$livable</td>
	</tr>
	<tr><th>Active</th>
		<td>$active</td>
	</tr>
";
