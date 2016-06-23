<?php
/**
 * @copyright 2009-2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @param GET contact_id
 */
$contact  = new Contact($_GET['contact_id']);
$template = new Template('two-column');
$template->blocks[]              = new Block('contacts/viewContactForm.inc', ['contact' => $contact]);
$template->blocks['panel-one'][] = new Block('changeLogs/changeLog.inc',     ['target'  => $contact]);
echo $template->render();
