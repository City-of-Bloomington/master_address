<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
$template = new Template();

$template->blocks[] = new Block('buildings/findBuildingForm.inc');
if (isset($_GET['building'])) {
	$search = array();
	foreach ($_GET['building'] as $field=>$val) {
		if ($val) {
			$search[$field] = $val;
		}
	}
	if (count($search)) {
		$page = isset($_GET['page']) ? $_GET['page'] : 1;
		$buildingList = new BuildingList(null,10,$page);
		$buildingList->search($search);
		$template->blocks[] = new Block('buildings/buildingList.inc',
										array('buildingList'=>$buildingList));

		$pageNavigation = new Block('pageNavigation.inc');
		$pageNavigation->pages = $buildingList->getPaginator()->getPages();
		$pageNavigation->url = new URL($_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);
		$template->blocks[] = $pageNavigation;
	}
}

$buildingTypeList = new BuildingTypeList();
$buildingTypeList->find();
$template->blocks[] = new Block('buildings/buildingTypeList.inc',
								array('buildingTypeList'=>$buildingTypeList));

$buildingStatusList = new BuildingStatusList();
$buildingStatusList->find();
$template->blocks[] = new Block('buildings/buildingStatusList.inc',
								array('buildingStatusList'=>$buildingStatusList));

echo $template->render();
