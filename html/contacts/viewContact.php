<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @auhtor W. Sibo <sibow@bloomington.in.gov>
 * @param GET contact_id
 */


$contact = new Contact($_GET['contact_id']);

$template = new Template();
$template->blocks[] = new Block('contacts/viewContactForm.inc',array('contact'=>$contact));
echo $template->render();
