<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
$template = isset($_GET['format']) ? new Template('default',$_GET['format']) : new Template();

if ($template->outputFormat == 'html') {
	$template->blocks[] = new Block('streets/breadcrumbs.inc');
	$template->blocks[] = new Block('streets/findStreetForm.inc');
}

if (isset($_GET['streetName'])) {
	$fields = AddressList::parseAddress($_GET['streetName'],'streetNameOnly');
	if (count($fields)) {
		$streets = new StreetList($fields);
		$template->blocks[] = new Block('streets/streetList.inc',array('streetList'=>$streets));
	}
}

echo $template->render();
