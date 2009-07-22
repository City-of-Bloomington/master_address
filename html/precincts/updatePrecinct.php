<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param GET precinct_id
 */
if (!userIsAllowed('Precinct')) {
	$_SESSION['errorMessages'][] = new Exception('noAccessAllowed');
	header('Location: '.BASE_URL.'/precincts');
	exit();
}

$precinct = new Precinct($_REQUEST['precinct_id']);
if (isset($_POST['precinct'])) {
	foreach ($_POST['precinct'] as $field=>$value) {
		$set = 'set'.ucfirst($field);
		$precinct->$set($value);
	}

	try {
		$precinct->save();
		header('Location: '.BASE_URL.'/precincts');
		exit();
	}
	catch (Exception $e) {
		$_SESSION['errorMessages'][] = $e;
	}
}

$template = new Template();
$template->blocks[] = new Block('precincts/updatePrecinctForm.inc',array('precinct'=>$precinct));
echo $template->render();