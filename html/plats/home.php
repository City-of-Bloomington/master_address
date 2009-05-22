<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
$template = new Template();
$template->blocks[] = new Block('plats/findPlatForm.inc');

if (isset($_GET['plat'])) {
	$search = array();
	foreach ($_GET['plat'] as $field=>$val) {
		if ($val) {
			$search[$field] = $val;
		}
	}
	if (count($search)) {
		$platList = new PlatList();
		$platList->search($search);
		$template->blocks[] = new Block('plats/platList.inc',array('platList'=>$platList));
	}
}

echo $template->render();
