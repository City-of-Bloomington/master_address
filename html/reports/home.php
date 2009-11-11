<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @author W Sibo <sibow@bloomington.in.gov>
 */
$template = isset($_GET['format'])
			? new Template('default',$_GET['format'])
			: new Template('two-column');


if (isset($_GET['actions'])) {
	$actions = $_GET['actions'];

	$types = array();
	if (isset($_GET['types'])) {
		$types = $_GET['types'];
	}

	$fields = array();
	if (isset($_GET['dateFrom'])) {
		if (is_array($_GET['dateFrom'])) {
			if ($_GET['dateFrom']['mon']
				&& $_GET['dateFrom']['mday'] && $_GET['dateFrom']['year']) {
				$fields['dateFrom'] = new Date($_GET['dateFrom']);
			}
		}
	}
	if (isset($_GET['dateTo'])) {
		if (is_array($_GET['dateTo'])) {
			if ($_GET['dateTo']['mon'] && $_GET['dateTo']['mday']
				&& $_GET['dateFrom']['year']) {
				$fields['dateTo'] = new Date($_GET['dateTo']);
			}
		}
	}

	if ($template->outputFormat == 'html') {
		$changeLogBlock = new Block('changeLogs/changeLog.inc',
									array('paginator'=>ChangeLog::getPaginator($types,$actions,$fields),
											'url'=>"http://$_SERVER[SERVER_NAME]$_SERVER[REQUEST_URI]"));
	}
	else {
		$changeLogBlock = new Block('changeLogs/changeLog.inc',
									array('changeLog'=>ChangeLog::getEntries($types,$actions,$fields)));
	}
}

if ($template->outputFormat == 'html') {
	$template->blocks[] = new Block('reports/reportForm.inc');
	if (isset($changeLogBlock)) {
		$template->blocks['panel-one'][] = $changeLogBlock;
	}
}
elseif (isset($changeLogBlock)) {
	$template->blocks[] = $changeLogBlock;
}

echo $template->render();
