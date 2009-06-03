<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */

verifyUser('Administrator');

$addrAssignmentContact = new AddrAssignmentContact($_REQUEST['contact_id']);
if (isset($_POST['mast_addr_assignment_contact'])) {
	foreach ($_POST['addrAssignmentContact'] as $field=>$value) {
		$set = 'set'.ucfirst($field);
		$addrAssignmentContact->$set($value);
	}

	try {
		$addrAssignmentContact->save();
		header('Location: '.BASE_URL.'/addressChange');
		exit();
	}
	catch (Exception $e) {
		$_SESSION['errorMessages'][] = $e;
	}
}

$template = new Template();
$template->blocks[] = new Block('addressChange/updateAddrAssignmentContactForm.inc',array('addrAssignmentContact'=>$addrAssignmentContact));
echo $template->render();