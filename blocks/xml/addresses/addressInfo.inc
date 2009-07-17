<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Address $this->address
 */
$township = $this->address->getTownship()
			? View::escape($this->address->getTownship()->getName())
			: '';
echo "
<address>
	<id>{$this->address->getId()}</id>
	<streetAddress>{$this->address->getStreetAddress()}</streetAddress>
	<city>{$this->address->getCity()}</city>
	<state>{$this->address->getState()}</state>
	<zip>{$this->address->getZip()}</zip>
	<latitude>{$this->address->getLatitude()}</latitude>
	<longitude>{$this->address->getLongitude()}</longitude>
	<statePlane>
		<x>{$this->address->getState_plane_x_coordinate()}</x>
		<y>{$this->address->getState_plane_y_coordinate()}</y>
	</statePlane>
	<jurisdiction>{$this->address->getJurisdiction()->getDescription()}</jurisdiction>
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
			<purpose type=\"$type\">$description</purpose>
			";
		}
		echo "</purposes>";
	}
echo "</address>";