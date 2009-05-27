<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */

$buildingTypeList = new BuildingTypeList();
$buildingTypeList->find();

$buildingStatusList = new BuildingStatusList();
$buildingStatusList->find();

$template = new Template();
$template->blocks[] = new Block('buildings/buildingTypeList.inc',
								array('buildingTypeList'=>$buildingTypeList));
$template->blocks[] = new Block('buildings/buildingStatusList.inc',
								array('buildingStatusList'=>$buildingStatusList));
$template->blocks[] = new Block('buildings/findBuildingForm.inc');

if (isset($_GET['building'])) {
	$search = array();
	foreach ($_GET['building'] as $field=>$val) {
		if ($val) {
			$search[$field] = $val;
		}
	}
	if (count($search)) {
		$buildingList = new BuildingList();
		$buildingList->search($search);
		$template->blocks[] = new Block('buildings/buildingList.inc',array('buildingList'=>$buildingList));
	}
}

echo $template->render();
