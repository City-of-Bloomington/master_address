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
	<tr><th>Address Type</th>
		<td>{$this->address->getType()}</td>
	</tr>
	<tr><th>Trash Day</th>
		<td>{$this->address->getTrashDay()}</td>
	</tr>
	<tr><th>Recycle Week</th>
		<td>{$this->address->getRecycleWeek()}</td>
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
</table>
";