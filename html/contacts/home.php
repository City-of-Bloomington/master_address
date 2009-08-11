<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */

$contacts = new ContactList();
$contacts->find();

$template = new Template();
$template->blocks[] = new Block('contacts/contactList.inc',array('contactList'=>$contacts));
echo $template->render();
