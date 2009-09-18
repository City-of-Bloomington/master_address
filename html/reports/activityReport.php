<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @author W Sibo <sibow@bloomington.in.gov>
 * 
 */


if (isset($_POST['actions'])) {
	
	$actions = $_POST['actions'];
	$fields = array();
	$types = array();
	if(isset($_POST['dateFrom'])){
		if(is_array($_POST['dateFrom'])){
			if($_POST['dateFrom']['mon'] && $_POST['dateFrom']['mday'] &&
			    $_POST['dateFrom']['year']){
				$fields['dateFrom'] = new Date($_POST['dateFrom']);
			}
		}
	}
	if(isset($_POST['dateTo'])){
		if(is_array($_POST['dateTo'])){
			if($_POST['dateTo']['mon'] && $_POST['dateTo']['mday'] &&
			    $_POST['dateFrom']['year']){		
				$fields['dateTo'] = new Date($_POST['dateTo']);
			}
		}
	}
	if(isset($_POST['types'])){
		$types = $_POST['types'];
	}
	$changeLog = ChangeLog::getEntries($types,$actions,$fields);
	// print_r($changeLog);
	
	$template = new Template('two-column');
}
else {

	$template = new Template();
}

$template->blocks[] = new Block('reports/activityReport.inc');

if(isset($_POST['actions'])){
	if(isset($changeLog)){
		$template->blocks['panel-one'][] = new Block('changeLogs/changeLog.inc',array('changeLog'=>$changeLog));
	}
	
}

echo $template->render();


