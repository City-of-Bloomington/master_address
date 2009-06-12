<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */

verifyUser('Administrator');

if (isset($_POST['precinct'])) {
	$precinct = new Precinct();
	foreach ($_POST['precinct'] as $field=>$value) {
		$set = 'set'.ucfirst($field);
		$precinct->$set($value);
	}

	try {
		$precinct->save();
		header('Location: '.BASE_URL.'/precincts');
		exit();
	}
	catch(Exception $e) {
		$_SESSION['errorMessages'][] = $e;
	}
}

$template = new Template();
$template->blocks[] = new Block('precincts/addPrecinctForm.inc');
echo $template->render();