<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */

$jurisdictionList = new JurisdictionList();
$jurisdictionList->find();

$template = new Template();
$template->blocks[] = new Block('jurisdictions/jurisdictionList.inc',
								array('jurisdictionList'=>$jurisdictionList));
echo $template->render();
