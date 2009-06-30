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
		$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
		$platList = new PlatList(null,10,$page);
		$platList->search($search);
		$template->blocks[] = new Block('plats/platList.inc',array('platList'=>$platList));

		$pageNavigation = new Block('pageNavigation.inc');
		$pageNavigation->pages = $platList->getPaginator()->getPages();
		$pageNavigation->url = new URL($_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);
		$template->blocks[] = $pageNavigation;
	}
}

echo $template->render();
