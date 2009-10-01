<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param array $this->changeLog (An array of ChangeLogEntry's)
 */
echo "
<div class=\"interfaceBox\">
	<h1>Action History</h1>
";
if (count($this->changeLog)) {
	echo "
	<table>
	<tr><th>Action Date</th>
		<th></th>
		<th>Action</th>
		<th>User</th>
		<th>Contact</th>
		<th>Notes</th>
	</tr>
	";
	foreach ($this->changeLog as $logEntry) {
		$username = $logEntry->getUser()->getUsername();
		$notes = View::escape($logEntry->notes);
		$notes = $notes ? $notes:'&nbsp;';
		echo "
		<tr>
			<td>{$logEntry->action_date}</td>
			<td>{$logEntry->getTarget()}</td>
			<td>{$logEntry->action}</td>
			<td>$username</td>
			<td>{$logEntry->getContact()->getFirstname()}
				{$logEntry->getContact()->getLastname()}
			</td>
			<td>$notes</td>
		</tr>
		";
	}
	echo "</table>";
}
echo "
</div>
";