<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */

$subunitTypeList = new SubunitTypeList();
$subunitTypeList->find();

$template = new Template();
$template->blocks[] = new Block('subunits/subunitTypeList.inc',
								array('subunitTypeList'=>$subunitTypeList));
echo $template->render();