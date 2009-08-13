<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Address $this->address
 */
echo "
<table>
	<tr><th>Street</th>
		<td>{$this->address->getStreet()->getStreetName()}</td>
	</tr>
	<tr><th>Number</th>
		<td>{$this->address->getStreet_number()}</td>
	</tr>
	<tr><th>Status</th>
		<td>{$this->address->getStatus()}</td>
	</tr>
</table>
";
