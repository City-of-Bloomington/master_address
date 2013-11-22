<?php
/**
 * @copyright 2013 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Address $this->address
 */
$township = $this->address->getTownship()
			? View::escape($this->address->getTownship()->getName())
			: '';
$output = [
	'id'           => $this->address->getId(),
	'streetAddress'=> $this->address->getStreetAddress(),
	'city'         => $this->address->getCity(),
	'state'        => $this->address->getState(),
	'zip'          => $this->address->getZip(),
	'jurisdiction' => $this->address->getJurisdiction()->getDescription(),
	'trashDay'     => $this->address->getTrashDay(),
	'recycleWeek'  => $this->address->getRecycleWeek(),
	'censusBlock'  => $this->address->getCensus_block_fips_code(),
	'latitude'     => $this->address->getLatitude(),
	'longitude'    => $this->address->getLongitude(),
	'statePlane' => [ 'x'=>$this->address->getState_plane_x_coordinate(),
					  'y'=>$this->address->getState_plane_y_coordinate()],
	'township'     => $township
];
$purposes = $this->address->getPurposes();
if (count($purposes)) {
	$p = [];
	foreach ($purposes as $purpose) {
		$type        = View::escape($purpose->getType());
		$description = View::escape($purpose->getDescription());
		$p[] = ['type'=>$type,'description'=>$description];
	}
	$output['purposes'] = $p;
}

$subunits = $this->address->getSubunits();
if (count($subunits)) {
	$s = [];
	foreach ($subunits as $subunit) {
		$s[] = ['id'        => $subunit->getId(),
				'type'      => $subunit->getType(),
				'identifier'=> $subunit->getIdentifier()];
	}
	$output['subunits'] = $s;
}
echo json_encode($output);
