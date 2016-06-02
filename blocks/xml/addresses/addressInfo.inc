<?php
/**
 * @copyright 2009-2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @param Address $this->address
 */
$fields = [
    'id', 'streetAddress',
    'street_number_prefix', 'street_number', 'street_number_suffix', 'street_id',
    'city', 'state', 'zip', 'zipplus4',
    'plat', 'plat_lot_number', 'section', 'quarter_section',
    'trashDay', 'recycleWeek',
    'latitude', 'longitude',
    'status', 'type', 'jurisdiction', 'township',
    'mailable', 'livable'
];
$output = [];
foreach ($fields as $f) {
    $get = 'get'.ucfirst($f);
    $$f = self::escape($this->address->$get());
}

$active       = $this->address->isActive() ? 'yes' : 'no';
$location     = $this->address->getLocation();
$locationType = $location ? $location->getLocationType($this->address) : '';

echo "
<address>
";
foreach ($fields as $f) {
    echo "\t<$f>{$$f}</$f>\n";
}
$streetName = $this->address->getStreetName();
$streetNameFields = ['directionCode', 'name', 'typeCode', 'postDirectionCode'];
echo "<streetName>";
foreach ($streetNameFields as $f) {
    $get = 'get'.ucfirst($f);
    echo "\t<$f>".self::escape($streetName->$get())."</$f>\n";
}
echo "</streetName>";

echo "
	<locationUseType>$locationType</locationUseType>
	<active>$active</active>
	<statePlane>
		<x>{$this->address->getState_plane_x_coordinate()}</x>
		<y>{$this->address->getState_plane_y_coordinate()}</y>
	</statePlane>
";
	$purposes = $this->address->getPurposes();
	if (count($purposes)) {
		echo "<purposes>\n";
		foreach ($purposes as $purpose) {
			$type = self::escape($purpose->getType());
			$desc = self::escape($purpose->getDescription());
			echo "\t<purpose id=\"{$purpose->getId()}\" type=\"$type\">$desc</purpose>\n";
		}
		echo "</purposes>";
	}

	$subunits = $this->address->getSubunits();
	if (count($subunits)) {
		echo "<subunits>";
		foreach ($subunits as $subunit) {
			$type       = self::escape($subunit->getType());
			$identifier = self::escape($subunit->getIdentifier());
			echo "
			<subunit id=\"{$subunit->getId()}\">
				<type>$type</type>
				<identifier>$identifier</identifier>
				<latitude>{$subunit->getLatitude()}</latitude>
				<longitude>{$subunit->getLongitude()}</longitude>
				<statePlane>
					<x>{$subunit->getState_plane_x_coordinate()}</x>
					<y>{$subunit->getState_plane_y_coordinate()}</y>
				</statePlane>
			</subunit>
			";
		}
		echo "</subunits>";
	}
echo "</address>";
