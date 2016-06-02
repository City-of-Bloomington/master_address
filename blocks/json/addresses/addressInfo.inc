<?php
/**
 * @copyright 2013-2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param Address $this->address
 */
$township = $this->address->getTownship()
			? $this->address->getTownship()->getName()
			: '';
$location = $this->address->getLocation();

$fields = [
    'id', 'streetAddress',
    'street_number_prefix', 'street_number', 'street_number_suffix', 'street_id',
    'city', 'state', 'zip', 'zipplus4',
    'plat', 'plat_lot_number', 'section', 'quarter_section',
    'trashDay', 'recycleWeek',
    'latitude', 'longitude'
];
$output = [];
foreach ($fields as $f) {
    $get = 'get'.ucfirst($f);
    $output[$f] = $this->address->$get();
}
$output['township'       ] = $township;
$output['mailable'       ] = $this->address->getMailable()=='yes' ? true : false;
$output['livable'        ] = $this->address->getLivable ()=='yes' ? true : false;
$output['active'         ] = $this->address->isActive()           ? true : false;
$output['status'         ] = $this->address->getStatus()->__toString();
$output['jurisdiction'   ] = $this->address->getJurisdiction()->getDescription();
$output['locationUseType'] = $location ? $location->getLocationType($this->address) : '';
$output['locations'      ] = [];
$output['statePlane'     ] = [ 'x' => $this->address->getState_plane_x_coordinate(),
                               'y' => $this->address->getState_plane_y_coordinate()];

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
