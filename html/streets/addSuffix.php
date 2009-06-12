<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */

verifyUser('Administrator');

if (isset($_POST['suffix'])) {
	$suffix = new Suffix();
	foreach ($_POST['suffix'] as $field=>$value) {
		$set = 'set'.ucfirst($field);
		$suffix->$set($value);
	}

	try {
		$suffix->save();
		header('Location: '.BASE_URL.'/streets');
		exit();
	}
	catch(Exception $e) {
		$_SESSION['errorMessages'][] = $e;
	}
}

$template = new Template();
$template->blocks[] = new Block('streets/addSuffixForm.inc');
echo $template->render();