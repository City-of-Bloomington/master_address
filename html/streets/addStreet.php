<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @author W. Sibo <sibow@bloomington.in.gov>
 */
if (!userIsAllowed('Street')) {
	$_SESSION['errorMessages'][] = new Exception('noAccessAllowed');
	header('Location: '.BASE_URL.'/streets');
	exit();
}

// All actions will involve updating the change log
if (isset($_POST['changeLogEntry'])) {
	try {
		$changeLogEntry = new ChangeLogEntry($_SESSION['USER'],$_POST['changeLogEntry']);
		$street = Street::createNew($_POST,$changeLogEntry);

		header('Location: '.$street->getURL());
		exit();
	}
	catch(Exception $e) {
		$_SESSION['errorMessages'][] = $e;
	}
}


$template = new Template();
$template->blocks[] = new Block('streets/breadcrumbs.inc',array('action'=>'add'));
$template->blocks[] = new Block('streets/addStreetForm.inc');
echo $template->render();
