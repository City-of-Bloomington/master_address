<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @author W Sibo <sibow@bloomington.in.gov>
 */
if (!userIsAllowed('Subunit')) {
	$_SESSION['errorMessages'][] = new Exception('noAccessAllowed');
	header('Location: '.BASE_URL.'/subunits');
	exit();
}
$address = new Address($_REQUEST['street_address_id']);

if (isset($_POST['changeLogEntry'])) {
	try {
		$changeLogEntry = new ChangeLogEntry($_SESSION['USER'],$_POST['changeLogEntry']);

		$identifiers = explode(',',$_POST['street_subunit_identifier']);
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
				$location->assign($subunit,$locationType);
				$location->activate($subunit);
				$data['mailable'] = isset($_POST['mailable']);
				$data['livable'] = isset($_POST['livable']);
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
$template->blocks[] = new Block('subunits/breadcrumbs.inc',array('address'=>$address));
$template->blocks[] = new Block('subunits/addSubunitForm.inc',array('address'=>$address));

$template->blocks['panel-one'][] = new Block('subunits/subunitList.inc',
											array('subunitList'=>$address->getSubunits()));
echo $template->render();