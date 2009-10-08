<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param GET plat_id
 */
try {
	if (isset($_GET['plat_id']) && $_GET['plat_id']) {
		$plat = new Plat($_GET['plat_id']);
	}
	else {
		throw new Exception('plats/unknownPlat');
	}
}
catch (Exception $e) {
	$_SESSION['errorMessages'][] = $e;
	header('Location: '.BASE_URL.'/plats');
}

$template = new Template('two-column');
$template->blocks[] = new Block('plats/platInfo.inc',array('plat'=>$plat));
$template->blocks['panel-one'][] = new Block('addresses/addressList.inc',
											array('addressList'=>$plat->getAddresses()));
echo $template->render();