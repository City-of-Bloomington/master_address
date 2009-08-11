<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */

if (!userIsAllowed('Contact')) {
	$_SESSION['errorMessages'][] = new Exception('noAccessAllowed');
	header('Location: '.BASE_URL.'/contacts');
	exit();
}

$contact = new Contact($_REQUEST['contact_id']);
if (isset($_POST['contact'])) {
	foreach ($_POST['contact'] as $field=>$value) {
		$set = 'set'.ucfirst($field);
		$contact->$set($value);
	}

	try {
		$contact->save();
		header('Location: '.BASE_URL.'/contacts');
		exit();
	}
	catch (Exception $e) {
		$_SESSION['errorMessages'][] = $e;
	}
}

$template = new Template();
$template->blocks[] = new Block('contacts/updateContactForm.inc',array('contact'=>$contact));
echo $template->render();
