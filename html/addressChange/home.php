<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */

$addressLocationChangeList = new AddressLocationChangeList();
$addressLocationChangeList->find();
$contactList = new ContactList();
$contactList->find();

$template = new Template();
$template->blocks[] = new Block('addressChange/addressLocationChangeList.inc',array('addressLocationChangeList'=>$addressLocationChangeList));
$template->blocks[] = new Block('addressChange/contactList.inc',array('contactList'=>$contactList));

echo $template->render();
