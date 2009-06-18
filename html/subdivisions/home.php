<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
$template = new Template();
$template->blocks[] = new Block('subdivisions/findSubdivisionForm.inc');
if (isset($_GET['subdivisionName'])) {
	$search = array();
	foreach ($_GET['subdivisionName'] as $field=>$val) {
		if ($val) {
			$search[$field] = $val;
		}
	}
	if (count($search)) {
		$subdivisionNameList = new SubdivisionNameList();
		$subdivisionNameList->search($search);
		$template->blocks[] = new Block('subdivisions/subdivisionNameList.inc',array('subdivisionNameList'=>$subdivisionNameList));
	}
}

echo $template->render();