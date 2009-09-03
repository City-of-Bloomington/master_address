<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param array $this->changeLog (An array of ChangeLogEntry's)
 */
echo "
<div class=\"interfaceBox\">
	<h1>History</h1>
";
if (count($this->changeLog)) {
	$type = $this->changeLog[0]->type ? '<th>Type</th>' : '';
	echo "
	<table>
	<tr>$type
		<th>Action Date</th>
		<th>Action</th>
		<th>User</th>
		<th>Notes</th>
	</tr>
	";
	foreach ($this->changeLog as $logEntry) {
		$username = $logEntry->getUser()->getUsername();
		$notes = View::escape($logEntry->notes);

		$type = $logEntry->type ? "<td>{$logEntry->type}</td>" : '';

		echo "
		<tr>$type
			<td>{$logEntry->action_date}</td>
			<td>{$logEntry->action}</td>
			<td>$username</td>
			<td>$notes</td>
		</tr>
		";
	}
	echo "</table>";
}
echo "
</div>
";