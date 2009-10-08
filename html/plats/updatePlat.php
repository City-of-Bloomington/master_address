<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param GET plat_id
 */
if (!userIsAllowed('Plat')) {
	$_SESSION['errorMessages'][] = new Exception('noAccessAllowed');
	header('Location: '.BASE_URL.'/plats');
	exit();
}

$plat = (isset($_REQUEST['plat_id']) && $_REQUEST['plat_id'])
		? new Plat($_REQUEST['plat_id'])
		: new Plat();

if (isset($_POST['plat'])) {
	foreach ($_POST['plat'] as $field=>$value) {
		$set = 'set'.ucfirst($field);
		$plat->$set($value);
	}

	try {
		$plat->save();
		header('Location: '.$plat->getURL());
		exit();
	}
	catch (Exception $e) {
		$_SESSION['errorMessages'][] = $e;
	}
}

$template = new Template();
$template->blocks[] = new Block('plats/platForm.inc',array('plat'=>$plat));
echo $template->render();
