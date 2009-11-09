<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
$template = isset($_GET['format']) ? new Template('default',$_GET['format']) : new Template();

$contacts = new ContactList();
if (isset($_REQUEST['name'])) {
	$contacts->find(array('name'=>$_REQUEST['name']));
}
else {
	$contacts->find();
}

$template->blocks[] = new Block('contacts/contactList.inc',array('contactList'=>$contacts));
echo $template->render();
