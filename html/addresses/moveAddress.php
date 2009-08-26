<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @author W. Sibo <sibow@boomington.in.gov>
 * @param GET address
 */

$template = new Template();
$location = new Location($_REQUEST['lid']);
if (isset($_GET['address'])) {
	$addresses = new AddressList();
	$addresses->search(array('address'=>$_GET['address']));

	$list = arry();
	foreach($addrsses as $address){
		$locationList = $address->getLocations();
		$list = array_merge($list, $locationList);
	}
}
if(isset($list)){
	$template->blocks[] = new Block('addresses/moveAddressForm.inc',
										array('locationList'=>$list,'location'=>$location));
}
else{
	$template->blocks[] = new Block('addresses/moveAddressForm.inc',
										array('location'=>$location));
}

echo $template->render();
