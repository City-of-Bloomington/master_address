<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @author W Sibo <sibow@bloomington.in.gov>
 * 
 */


if (isset($_POST['show'])) {
	
	// $report = new Report($_POST);
	
	$template = new Template('two-column');
}
else {

	$template = new Template();
}



$template->blocks[] = new Block('reports/activityReport.inc');

if(isset($_POST['show'])){
	// $template->blocks['panel-one'][] = new Block('reports/reportInfo.inc');
}

echo $template->render();


