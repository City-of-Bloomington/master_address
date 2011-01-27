<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Address $this->address
 */
$address = View::escape($this->address->getStreetAddress());
$city = View::escape($this->address->getCity());
$state = View::escape($this->address->getState());
$zip = View::escape($this->address->getZip());
$jurisdiction = View::escape($this->address->getJurisdiction()->getDescription());
$township = $this->address->getTownship()
			? View::escape($this->address->getTownship()->getName())
			: '';
echo "
<address>
	<id>{$this->address->getId()}</id>
	<streetAddress>$address</streetAddress>
	<city>$city</city>
	<state>$state</state>
	<zip>$zip</zip>
	<latitude>{$this->address->getLatitude()}</latitude>
	<longitude>{$this->address->getLongitude()}</longitude>
	<statePlane>
		<x>{$this->address->getState_plane_x_coordinate()}</x>
		<y>{$this->address->getState_plane_y_coordinate()}</y>
	</statePlane>
	<jurisdiction>$jurisdiction</jurisdiction>
	<township>$township</township>
	<trashDay>{$this->address->getTrashDay()}</trashDay>
	<recycleWeek>{$this->address->getRecycleWeek()}</recycleWeek>
	<censusBlock>{$this->address->getCensus_block_fips_code()}</censusBlock>
";
	$purposes = $this->address->getPurposes();
	if (count($purposes)) {
		echo "<purposes>";
		foreach ($purposes as $purpose) {
			$type = View::escape($purpose->getType());
			$description = View::escape($purpose->getDescription());
			echo "
			<purpose id=\"{$purpose->getId()}\" type=\"$type\">$description</purpose>
			";
		}
		echo "</purposes>";
	}

	$subunits = $this->address->getSubunits();
	if (count($subunits)) {
		echo "<subunits>";
		foreach ($subunits as $subunit) {
			$type = View::escape($subunit->getType());
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