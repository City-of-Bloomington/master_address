<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param GET street_id
 */
$street = new Street($_GET['street_id']);

$template = new Template('two-column');
$template->blocks['panel-one'] = new Block('streets/streetInfo.inc',array('street'=>$street));
$template->blocks['panel-one'] = new Block('streets/streetNameList.inc',
										   array('streetNameList'=>$street->getNames(),'street'=>$street));
$template->blocks['content-panel'] = new Block('addresses/addressList.inc',
											   array('addressList'=>$street->getAddresses()));
echo $template->render();
