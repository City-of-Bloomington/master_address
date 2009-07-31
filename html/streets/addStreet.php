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

if (isset($_POST['street'])) {
	$street = new Street();
	foreach ($_POST['street'] as $field=>$value) {
		$set = 'set'.ucfirst($field);
		$street->$set($value);
	}
	try {
		$street->save();
        if (isset($_POST['streetName'])) {
        	$streetName = new StreetName(); 
            foreach ($_POST['streetName'] as $field=>$value) {
                $set = 'set'.ucfirst($field);
                $streetName->$set($value);
            }
        }
        $streetName->setStreet_id($street->getStreet_id());
        $streetName->save();
		header('Location: '.BASE_URL.'/streets');
		exit();
		
	}
	catch(Exception $e) {
		$_SESSION['errorMessages'][] = $e;
	}
}


$template = new Template();

$template->blocks[] = new Block('streets/addStreetForm.inc');
echo $template->render();