<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */

$annexationList = new AnnexationList();
$annexationList->find();

$template = new Template();
$template->blocks[] = new Block('annexations/annexationList.inc',
								array('annexationList'=>$annexationList));
echo $template->render();