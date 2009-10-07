<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param array $this->changeLog (An array of ChangeLogEntry's)
 */
echo "Date|Action|Target|Username|Contact|Notes\n";
foreach ($this->changeLog as $logEntry) {
	$username = $logEntry->getUser()->getUsername();
	$notes = preg_replace('/\n/',' ',$logEntry->notes);
	$contact = $logEntry->getContact()->getFirstname().' '.$logEntry->getContact()->getLastname();

	$line = array($logEntry->action_date,$logEntry->action,$logEntry->getTarget(),
					$username,$contact,$notes);
	$line = implode('|',$line);
	echo "$line\n";
}