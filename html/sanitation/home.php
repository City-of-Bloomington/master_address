<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */

$addressSanitationList = new AddressSanitationList();
$addressSanitationList->find(null,null,15); // limit 15 for now

$template = new Template();
$template->blocks[] = new Block('sanitation/addressSanitationList.inc',array('addressSanitationList'=>$addressSanitationList));
echo $template->render();