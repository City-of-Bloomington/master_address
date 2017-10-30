<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param array $this->changeLog (An array of ChangeLogEntry's)
 */
echo "
<activity>
";
foreach ($this->changeLog as $logEntry) {
	$username = $logEntry->getUser()->getUsername();
	$notes = View::escape($logEntry->notes);
	$notes = $notes ? $notes : '';
	echo "
	<action type=\"{$logEntry->action}\">
		<date>{$logEntry->action_date}</date>
		<target>{$logEntry->getTarget()}</target>
		<username>$username</username>
		<contact>
			{$logEntry->getContact()->getFirstname()}
			{$logEntry->getContact()->getLastname()}
		</contact>
		<notes>$notes</notes>
	</action>
	";
}
echo "
</activity>
";
