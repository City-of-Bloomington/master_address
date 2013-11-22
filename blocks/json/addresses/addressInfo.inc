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
$location = $this->address->getLocation();
$locationType = $location ? $location->getLocationType($this->address) : '';
$output = [
	'id'             => $this->address->getId(),
	'streetAddress'  => $this->address->getStreetAddress(),
	'city'           => $this->address->getCity(),
	'state'          => $this->address->getState(),
	'zip'            => $this->address->getZip(),
	'status'         => $this->address->getStatus(),
	'jurisdiction'   => $this->address->getJurisdiction()->getDescription(),
	'plat'           => $this->address->getPlat(),
	'lot'            => $this->address->getPlat_lot_number(),
	'section'        => $this->address->getSection(),
	'quarter'        => $this->address->getQuarter_section(),
	'trashDay'       => $this->address->getTrashDay(),
	'recycleWeek'    => $this->address->getRecycleWeek(),
	'censusBlock'    => $this->address->getCensus_block_fips_code(),
	'township'       => $this->address->getTownship(),
	'latitude'       => $this->address->getLatitude(),
	'longitude'      => $this->address->getLongitude(),
	'censusBlock'    => $this->address->getCensus_block_fips_code(),
	'taxJurisdiction'=> $this->address->getTax_jurisdiction(),
	'mailable'       => $this->address->getMailable(),
	'livable'        => $this->address->getLivable(),
	'active'         => $this->address->isActive() ? 'yes' : 'no',
	'statePlane' => [ 'x'=>$this->address->getState_plane_x_coordinate(),
					  'y'=>$this->address->getState_plane_y_coordinate()],
	'locationUseType' => $location ? $location->getLocationType($this->address) : '',
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
