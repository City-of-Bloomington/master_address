<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
$purposeList = new PurposeList();
$purposeList->find();

$template = new Template();
$template->blocks[] = new Block('locations/purposeList.inc',array('purposeList'=>$purposeList));
echo $template->render();