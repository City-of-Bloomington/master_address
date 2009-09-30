<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
if (!userIsAllowed('Street')) {
	$_SESSION['errorMessages'][] = new Exception('noAccessAllowed');
	header('Location: '.BASE_URL.'/streets');
	exit();
}

$streetName = new StreetName($_REQUEST['streetName_id']);
$street = $streetName->getStreet();

if (isset($_POST['street_name'])) {
	$fields = array('street_direction_code','street_name','street_type_suffix_code',
					'post_direction_suffix_code');
	foreach ($fields as $field) {
		if (isset($_POST[$field])) {
			$set = 'set'.ucfirst($field);
			$streetName->$set($_POST[$field]);
		}
	}

	try {
		$streetName->save();
		header('Location: '.$street->getURL());
		exit();
	}
	catch (Exception $e) {
		$_SESSION['errorMessages'][] = $e;
	}
}


$template = new Template('two-column');
$template->blocks[] = new Block('streets/breadcrumbs.inc',array('street'=>$street));
$template->blocks[] = new Block('streets/streetInfo.inc',array('street'=>$street));
$template->blocks[] = new Block('changeLogs/changeLog.inc',
								array('changeLog'=>$street->getChangeLog()));

$template->blocks['panel-one'][] = new Block('streets/updateStreetNameForm.inc',
											array('streetName'=>$streetName));

$template->blocks['panel-one'][] = new Block('addresses/addressList.inc',
											array('addressList'=>$street->getAddresses(),
													'street'=>$street));
echo $template->render();
