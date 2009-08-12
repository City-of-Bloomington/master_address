<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Address $this->address
 */
echo "
<div class=\"interfaceBox\">
	<h1>History</h1>
	<table>
";
foreach ($this->address->getChangeLog() as $logEntry) {
	$username = $logEntry->getUser()->getUsername();
	$rationale = View::escape($logEntry->rationale);

	echo "
	<tr><td>{$logEntry->date_changed}</td>
		<td>{$logEntry->action}</td>
		<td>$rationale</td>
		<td>$username</td>
	</tr>
	";
}
echo "
	</table>
</div>
";