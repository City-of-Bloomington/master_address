<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param AddressList $this->addressList
 */
$addresses = array();
foreach ($this->addressList as $address) {
	$township = $address->getTownship()
				? View::escape($address->getTownship()->getName())
				: '';
	$output = array('id'=>$address->getId(),
					'streetAddress'=>$address->getStreetAddress(),
					'city'=>$address->getCity(),
					'state'=>$address->getState(),
					'zip'=>$address->getZip(),
					'latitude'=>$address->getLatitude(),
					'longitude'=>$address->getLongitude(),
					'statePlane'=>array('x'=>$address->getState_plane_x_coordinate(),
										'y'=>$address->getState_plane_y_coordinate()),
					'jurisdiction'=>$address->getJurisdiction()->getDescription(),
					'township'=>$township,
					'trashDay'=>$address->getTrashDay(),
					'recycleWeek'=>$address->getRecycleWeek(),
					'censusBlock'=>$address->getCensus_block_fips_code());
	$purposes = $address->getPurposes();
	if (count($purposes)) {
		$p = array();
		foreach ($purposes as $purpose) {
			$type = View::escape($purpose->getType());
			$description = View::escape($purpose->getDescription());
			$p[] = array('type'=>$type,'description'=>$description);
		}
		$output['purposes'] = $p;
	}

	$subunits = $address->getSubunits();
	if (count($subunits)) {
		$s = array();
		foreach ($subunits as $subunit) {
			$s[] = array('id'=>$subunit->getId(),
						'type'=>$subunit->getType(),
						'identifier'=>$subunit->getIdentifier());
		}
		$output['subunits'] = $s;
	}
	$addresses[] = $output;
}
echo json_encode($addresses);
