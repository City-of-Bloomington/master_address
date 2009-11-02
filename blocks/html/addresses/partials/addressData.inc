<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Address $this->address
 */
$active = $this->address->isActive()? 'Yes' : 'No';
echo "
<table>
	<tr><th>Tax Jurisdiction</th><td> {$this->address->getTax_jurisdiction()}</td></tr>
        <tr><th>Location Use Type</th><td>{$this->address->getLocation()->getLocationType($this->address)}</td></tr>
	<tr><td><label>Trash Day</label></td><td> {$this->address->getTrashDay()}</td>
	<td><label>Recycle Week<?label></td><td> {$this->address->getRecycleWeek()}</td>
	</tr>
	<tr><td><label>Mailable</label></td><td> {$this->address->getMailable()} </td><td><label>Livable </label></td><td>{$this->address->getLivable()}</td>
	</tr>
	<tr><th>Active</th>
		<td>$active</td>
	</tr>
</table>
";