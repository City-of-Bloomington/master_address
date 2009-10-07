<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param array $this->changeLog (An array of ChangeLogEntry's)
 * @param URL $this->url
 */
$txtButton = '';
$xmlButton = '';
if (isset($this->url)) {
	$url = $this->url.';format=txt';
	$txtButton = "
		<button type=\"button\" onclick=\"window.open('$url','popup');\">
			TXT
		</button>
	";

	$url = $this->url.';format=xml';
	$xmlButton = "
		<button type=\"button\" onclick=\"window.open('$url','popup');\">
			XML
		</button>
	";
}
echo "
<div class=\"interfaceBox\">
	<h1>Action History $txtButton $xmlButton</h1>
";
if (count($this->changeLog)) {
	echo "
	<table>
		<thead>
			<tr><th>Action Date</th>
				<th></th>
				<th>Action</th>
				<th>User</th>
				<th>Contact</th>
				<th>Notes</th>
			</tr>
		</thead>
		<tbody>
	";
		foreach ($this->changeLog as $logEntry) {
			$username = $logEntry->getUser()->getUsername();
			$notes = View::escape($logEntry->notes);
			$notes = $notes ? $notes:'&nbsp;';
			echo "
			<tr><td>{$logEntry->action_date}</td>
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
	echo "
		</tbody>
	</table>
	";
}
echo "
</div>
";