<?php
/**
 * @copyright 2009-2013 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Address $this->address
 */
$address      = View::escape($this->address->getStreetAddress());
$city         = View::escape($this->address->getCity());
$state        = View::escape($this->address->getState());
$zip          = View::escape($this->address->getZip());
$status       = View::escape($this->address->getStatus());
$type         = View::escape($this->address->getType());
$jurisdiction = View::escape($this->address->getJurisdiction());
$plat         = View::escape($this->address->getPlat());
$lot          = View::escape($this->address->getPlat_lot_number());
$section      = View::escape($this->address->getSection());
$quarter      = View::escape($this->address->getQuarter_section());
$township     = View::escape($this->address->getTownship());

$active = $this->address->isActive() ? 'yes' : 'no';
$location = $this->address->getLocation();
$locationType = $location ? $location->getLocationType($this->address) : '';

echo "
<address>
	<id>{$this->address->getId()}</id>
	<streetAddress>$address</streetAddress>
	<city>$city</city>
	<state>$state</state>
	<zip>$zip</zip>
	<status>$status</status>
	<latitude>{$this->address->getLatitude()}</latitude>
	<longitude>{$this->address->getLongitude()}</longitude>
	<statePlane>
		<x>{$this->address->getState_plane_x_coordinate()}</x>
		<y>{$this->address->getState_plane_y_coordinate()}</y>
	</statePlane>
	<jurisdiction>$jurisdiction</jurisdiction>
	<plat>$plat</plat>
	<lot>$lot</lot>
	<section>$section</section>
	<quarterSection>$quarter</quarterSection>
	<township>$township</township>
	<trashDay>{$this->address->getTrashDay()}</trashDay>
	<recycleWeek>{$this->address->getRecycleWeek()}</recycleWeek>
	<censusBlock>{$this->address->getCensus_block_fips_code()}</censusBlock>
	<taxJurisdiction>{$this->address->getTax_jurisdiction()}</taxJurisdiction>
	<locationUseType>$locationType</locationUseType>
	<mailable>{$this->address->getMailable()}</mailable>
	<livable>{$this->address->getLivable()}</livable>
	<active>$active</active>
";
	$purposes = $this->address->getPurposes();
	if (count($purposes)) {
		echo "<purposes>\n";
		foreach ($purposes as $purpose) {
			$type = View::escape($purpose->getType());
			$desc = View::escape($purpose->getDescription());
			echo "\t<purpose id=\"{$purpose->getId()}\" type=\"$type\">$desc</purpose>\n";
		}
		echo "</purposes>";
	}

	$subunits = $this->address->getSubunits();
	if (count($subunits)) {
		echo "<subunits>";
		foreach ($subunits as $subunit) {
			$type       = View::escape($subunit->getType());
			$identifier = View::escape($subunit->getIdentifier());
			echo "
			<subunit id=\"{$subunit->getId()}\">
				<type>$type</type>
				<identifier>$identifier</identifier>
			</subunit>
			";
		}
		echo "</subunits>";
	}
echo "</address>";
