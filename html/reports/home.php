<?php
/**
 * @copyright 2009-2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 */
if (!userIsAllowed('Report')) {
	$_SESSION['errorMessages'][] = new Exception('noAccessAllowed');
	header('Location: '.BASE_URL);
	exit();
}

$template = isset($_GET['format'])
			? new Template('default',$_GET['format'])
			: new Template('two-column');


if (isset($_GET['actions'])) {
	$actions = $_GET['actions'];

	$types = [];
	if (isset(   $_GET['types'])) {
		$types = $_GET['types'];
	}

	$jurisdictions = (isset($_GET['jurisdictions'])) ? $_GET['jurisdictions'] : [];

	$fields = [];
	if (   !empty($_GET['dateFrom']['mon' ])
        && !empty($_GET['dateFrom']['mday'])
        && !empty($_GET['dateFrom']['year'])) {

        $fields['dateFrom'] = new Date($_GET['dateFrom']);
    }
    if (   !empty($_GET['dateTo']['mon' ])
        && !empty($_GET['dateTo']['mday'])
        && !empty($_GET['dateTo']['year'])) {

        $fields['dateTo'] = new Date($_GET['dateTo']);
    }

	$changeLogBlock = new Block('changeLogs/changeLog.inc');
	if ($template->outputFormat == 'html') {
        $changeLogBlock->paginator = ChangeLog::getPaginator($types, $actions, $fields, $jurisdictions);
        $changeLogBlock->url       = "http://$_SERVER[SERVER_NAME]$_SERVER[REQUEST_URI]";
	}
	else {
        $changeLogBlock->changeLog = ChangeLog::getEntries($types, $actions, $fields, $jurisdictions);
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
