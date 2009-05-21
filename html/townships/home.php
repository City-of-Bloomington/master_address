<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */

$townshipList = new TownshipList();
$townshipList->find();

$template = new Template();
$template->blocks[] = new Block('townships/townshipList.inc',array('townshipList'=>$townshipList));
echo $template->render();
