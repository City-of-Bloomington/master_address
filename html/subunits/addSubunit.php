<?php
/**
 * @copyright 2009-2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 */
if (!userIsAllowed('Subunit')) {
	$_SESSION['errorMessages'][] = new Exception('noAccessAllowed');
	header('Location: '.BASE_URL.'/subunits');
	exit();
}
$address = new Address($_REQUEST['street_address_id']);

if (isset($_POST['changeLogEntry'])) {
	try {
		$changeLogEntry = new ChangeLogEntry($_SESSION['USER'], $_POST['changeLogEntry']);

		$identifiers = explode(',', $_POST['subunit_identifier']);
		foreach ($identifiers as $identifier) {
			$identifier = trim($identifier);

			if ($identifier) {
				$subunit = new Subunit();
				$subunit->setAddress($address);
				$subunit->setIdentifier($identifier);
				$subunit->setSudtype($_POST['sudtype']);
				$subunit->setNotes($_POST['notes']);

				$subunit->save($changeLogEntry);
				$subunit->saveStatus('CURRENT');

				$locationType = new LocationType($_POST['location_type_id']);
				$location = new Location();
				$location->assign($subunit, $locationType);
				$location->activate($subunit);
				$data['mailable'] = $_POST['mailable'];
				$data['livable' ] = $_POST['livable'];
				$location->update($data,$subunit);
				$location->saveStatus('CURRENT');
			}
		}
	}
	catch (Exception $e) {
		$_SESSION['errorMessages'][] = $e;
		header('Location: '.$address->getURL());
		exit();
	}

	header('Location: '.$address->getURL());
	exit();
}

$template = new Template('two-column');
$template->blocks[] = new Block('subunits/breadcrumbs.inc',   ['address'=>$address]);
$template->blocks[] = new Block('subunits/addSubunitForm.inc',['address'=>$address]);

$template->blocks['panel-one'][] = new Block(
    'subunits/subunitList.inc', [
        'subunitList'=>$address->getSubunits(),
        'deactivateButtons'=>true
    ]
);
echo $template->render();
