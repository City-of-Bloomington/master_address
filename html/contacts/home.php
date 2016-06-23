<?php
/**
 * @copyright 2009-2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 */
if (!userIsAllowed('Contact')) {
	$_SESSION['errorMessages'][] = new Exception('noAccessAllowed');
	header('Location: '.BASE_URL);
	exit();
}

$template = isset($_GET['format'])
    ? new Template('default',$_GET['format'])
    : new Template();

$contacts = new ContactList();
if (isset( $_REQUEST['name'])) {
	$contacts->find(['name' => $_REQUEST['name']]);
}
else {
	$contacts->find();
}

$template->blocks[] = new Block('contacts/contactList.inc', ['contactList' => $contacts]);
echo $template->render();
