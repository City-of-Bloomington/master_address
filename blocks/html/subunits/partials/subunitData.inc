<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Address $this->address
 */
$notes = View::escape($this->subunit->getNotes());
echo "
<table>
	<tr><th>Type</th>
		<td>{$this->subunit->getSudtype()}</td>
	</tr>
	<tr><th>#</th>
		<td>{$this->subunit->getIdentifier()}</td>
	</tr>
	<tr><th>Status</th>
		<td>{$this->subunit->getStatus()}</td>
	</tr>
	<tr><th>Notes</th>
		<td>{$notes}</td>
	</tr>
</table>
";