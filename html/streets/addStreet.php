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

		$street = new Street();
		$street->setTown_id($_POST['town_id']);
		$street->setNotes($_POST['notes']);

		switch ($changeLogEntry->action) {
			case 'propose':
				$street->setStatus('PROPOSED');
				break;
			default:
				$street->setStatus('CURRENT');
		}
		$street->save($changeLogEntry);

		if (isset($_POST['streetName'])) {
			$streetName = new StreetName();
			$streetName->setStreet_name_type('STREET');
			foreach ($_POST['streetName'] as $field=>$value) {
				$set = 'set'.ucfirst($field);
				$streetName->$set($value);
			}
		}
		$streetName->setStreet_id($street->getId());
		$streetName->save();

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