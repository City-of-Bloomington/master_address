<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */

verifyUser('Administrator');

if (isset($_POST['addrAssignmentContact'])) {
	$addrAssignmentContact = new AddrAssignmentContact();
	foreach ($_POST['addrAssignmentContact'] as $field=>$value) {
		$set = 'set'.ucfirst($field);
		$addrAssignmentContact->$set($value);
	}

	try {
		$addrAssignmentContact->save();
		header('Location: '.BASE_URL.'/addressChange');
		exit();
	}
	catch(Exception $e) {
		$_SESSION['errorMessages'][] = $e;
	}
}

$template = new Template();
$template->blocks[] = new Block('addressChange/addAddrAssignmentContactForm.inc');
echo $template->render();