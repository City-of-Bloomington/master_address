<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */

verifyUser('Administrator');

if (isset($_POST['plat'])) {
	$plat = new Plat();
	foreach ($_POST['plat'] as $field=>$value) {
		$set = 'set'.ucfirst($field);
		$plat->$set($value);
	}

	try {
		$plat->save();
		header('Location: '.BASE_URL.'/plats');
		exit();
	}
	catch(Exception $e) {
		$_SESSION['errorMessages'][] = $e;
	}
}

$template = new Template();
$template->blocks[] = new Block('plats/addPlatForm.inc');
echo $template->render();
