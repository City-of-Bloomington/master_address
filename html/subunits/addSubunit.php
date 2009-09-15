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
$location = new Location();

if (isset($_POST['subunit'])) {
	$sudtype = "";
	$values = "";
	$notes = "";
	foreach ($_POST['subunit'] as $field=>$value) {
		if($field == "sudtype"){
			$sudtype = $value;
		}
		elseif($field == "street_subunit_identifier"){
			$values = $value;
		}
		elseif($field == "notes"){
			$notes = $value;
		}
	}
	$changeLog = new ChangeLogEntry($_SESSION['USER'],array('action'=>'assign'));
	$arr = explode(",", $values);
	foreach ($arr as $value){
		if($value){            // avoid empty strings
			$subunit = new Subunit();
			$subunit->setStreet_address_id($address->getId());
			$subunit->setSudtype($sudtype);
			$subunit->setStreet_subunit_identifier($value);
			$subunit->setNotes($notes);	
			try{
				$subunit->save($changeLog);
				$subunit->saveStatus('Current');
				$type = new LocationType($_POST['location_type_id']);
				$location = new Location();
				$location->assign($subunit, $type);
				$location->activateAddress($subunit);
				$data['mailable'] = isset($_POST['mailable']);
				$data['livable'] = isset($_POST['livable']);
				$location->update($data,$subunit);

			}
			catch(Exception $e) {
				$_SESSION['errorMessages'][] = $e;
				header('Location: '.$address->getURL());			
				exit();							
			}	
		}
	}
	
	if(!isset($_POST['batch_mode'])){
		header('Location: '.$address->getURL());			
		exit();			
	}	

}

$template = new Template('two-column');
$template->blocks[] = new Block('subunits/breadcrumbs.inc',array('address'=>$address));

$template->blocks[] = new Block('subunits/addSubunitForm.inc', array('address'=>$address,'location'=>$location));
$template->blocks['panel-one'][] = new Block('subunits/subunitList.inc',
													array('subunitList'=>$address->getSubunits()));
echo $template->render();