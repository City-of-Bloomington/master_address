<?php
/**
 * @copyright 2009-2014 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @author W. Sibo <sibow@bloomington.in.gov>
 */

if (!userIsAllowed('Contact')) {
	$_SESSION['errorMessages'][] = new Exception('noAccessAllowed');
	header('Location: '.BASE_URL.'/contacts');
	exit();
}

if (!empty($_REQUEST['contact_id'])) {
	try {
		$contact = new Contact($_REQUEST['contact_id']);
	}
	catch (Exception $e) {
		$_SESSION['errorMessages'][] = $e;
		header('Location: '.BASE_URL.'/contacts');
		exit();
	}
}
else {
	$contact = new Contact();
}


if (isset($_POST['status'])) {
	$contact->handleUpdate($_POST);

	try {
		$contact->save();
		header('Location: '.BASE_URL.'/contacts');
		exit();
	}
	catch (Exception $e) {
		$_SESSION['errorMessages'][] = $e;
	}

}

$template = new Template('two-column');
$template->blocks[] = new Block('contacts/updateContactForm.inc',array('contact'=>$contact));
if ($contact->getId()) {
	$template->blocks['panel-one'][] = new Block('changeLogs/changeLog.inc',['target'=>$contact]);
}
echo $template->render();
