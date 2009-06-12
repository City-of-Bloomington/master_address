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
		$buildingList = new BuildingList();
		$buildingList->search($search);
		$template->blocks[] = new Block('buildings/buildingList.inc',array('buildingList'=>$buildingList));
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
