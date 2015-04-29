<?php
/**
 * @copyright 2013-2015 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Address $this->address
 */
$township = $this->address->getTownship()
			? $this->address->getTownship()->getName()
			: '';
$location = $this->address->getLocation();
$output = [
	'id'             => $this->address->getId(),
	'streetAddress'  => $this->address->getStreetAddress(),
	'city'           => $this->address->getCity(),
	'state'          => $this->address->getState(),
	'zip'            => $this->address->getZip(),
	'status'         => $this->address->getStatus()->__toString(),
	'jurisdiction'   => $this->address->getJurisdiction()->getDescription(),
	'plat'           => $this->address->getPlat(),
	'lot'            => $this->address->getPlat_lot_number(),
	'section'        => $this->address->getSection(),
	'quarter'        => $this->address->getQuarter_section(),
	'trashDay'       => $this->address->getTrashDay(),
	'recycleWeek'    => $this->address->getRecycleWeek(),
	'township'       => $township,
	'latitude'       => $this->address->getLatitude(),
	'longitude'      => $this->address->getLongitude(),
	'mailable'       => $this->address->getMailable()=='yes' ? true : false,
	'livable'        => $this->address->getLivable ()=='yes' ? true : false,
	'active'         => $this->address->isActive()           ? true : false,
	'statePlane' => [ 'x'=>$this->address->getState_plane_x_coordinate(),
					  'y'=>$this->address->getState_plane_y_coordinate()],
	'locationUseType' => $location ? $location->getLocationType($this->address) : '',
	'locations'       => []
];

$locations = $this->address->getLocations();
foreach ($locations as $location) {
    foreach ($location->getAddresses() as $address) {
        $output['locations'][] = [
            'location_id' => $location->getLocation_id(),
            'address_id'  => $address->getId(),
            'active'      => $location->isActive($address)
        ];
    }
}

$purposes = $this->address->getPurposes();
if (count($purposes)) {
	$p = [];
	foreach ($purposes as $purpose) {
		$type        = $purpose->getType();
		$description = $purpose->getDescription();
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
				'identifier'=> $subunit->getIdentifier(),
				'latitude'  => $subunit->getLatitude(),
				'longitude' => $subunit->getLongitude(),
				'statePlane' => [
					'x' => $subunit->getState_plane_x_coordinate(),
					'y' => $subunit->getState_plane_y_coordinate()
				]
		];
	}
	$output['subunits'] = $s;
}
echo json_encode($output, JSON_PRETTY_PRINT);
