<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
$template = new Template();
$template->blocks[] = new Block('subdivisions/findSubdivisionForm.inc');
if (isset($_GET['sub'])) {
	$search = array();
	foreach ($_GET['sub'] as $field=>$val) {
		if ($val) {
			$search[$field] = $val;
		}
	}
	if (count($search)) {
		$subdivisionList = new SubdivisionList();
		$subdivisionList->search($search);
		$template->blocks[] = new Block('subdivisions/subdivisionList.inc',array('subdivisionList'=>$subdivisionList));
	}
}

echo $template->render();