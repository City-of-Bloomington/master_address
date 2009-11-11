<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param GET subunit_id
 */

$subunit = new Subunit($_REQUEST['subunit_id']);
$address = $subunit->getAddress();
$template = new Template('two-column');

$template->blocks[] = new Block('subunits/breadcrumbs.inc',array('subunit'=>$subunit));

$template->blocks[] = new Block('subunits/subunitInfo.inc',array('subunit'=>$subunit));

$template->blocks[] = new Block('subunits/subunitStatusChangeList.inc',
									array('subunitStatusChangeList'=>$subunit->getStatusChangeList()));

$template->blocks[] = new Block('changeLogs/changeLog.inc',array('target'=>$subunit));


$template->blocks['panel-one'][] = new Block('subunits/subunitList.inc',
												array('address'=>$address,
												'subunitList'=>$address->getSubunits()));

echo $template->render();

