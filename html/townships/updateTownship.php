<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param GET township_id
 */
if (!userIsAllowed('Township')) {
	$_SESSION['errorMessages'][] = new Exception('noAccessAllowed');
	header('Location: '.BASE_URL.'/townships');
	exit();
}

$township = new Township($_REQUEST['township_id']);
if (isset($_POST['township'])) {
	foreach ($_POST['township'] as $field=>$value) {
		$set = 'set'.ucfirst($field);
		$township->$set($value);
	}

	try {
		$township->save();
		header('Location: '.BASE_URL.'/townships');
		exit();
	}
	catch (Exception $e) {
		$_SESSION['errorMessages'][] = $e;
	}
}

$template = new Template();
$template->blocks[] = new Block('townships/updateTownshipForm.inc',array('township'=>$township));
echo $template->render();
