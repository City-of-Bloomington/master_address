<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param AddressList $this->addressList
 */
$street_id = isset($this->street) ? $this->street->getId() : '';
$addButton = '';
if (userIsAllowed('Address')) {
	$addButton = "
		<div>
			<button type=\"button\" onclick=\"document.location.href='".BASE_URL."/addresses/addAddress.php?street_id=$street_id';\">
				Add Address
			</button>
		</div>
		";
}
echo "
<div class=\"interfaceBox addressList\">
	<h1>Addresses</h1>
	$addButton
";
	if (count($this->addressList)) {
		echo "
		<table>
			<tr><th>#</th>
				<th>Type</th>
				<th>Status</th>
				<th>Jurisdiction</th>
				<th>Zip Code</th>
				<th>Sub Units</th>
			</tr>
	";
		foreach ($this->addressList as $address) {
			$street_number = $address->getStreet_number();
			$type = $address->getAddress_type();
			$status = $address->getStatus();
			$jurisdiction = $address->getJurisdiction();
			$zip = $address->getZip();
			$count = count($address->getSubunits());
			$count = $count ? $count : '';
			echo "
			<tr><td><a href=\"{$address->getURL()}\">$street_number</a></td>
				<td><a href=\"{$address->getURL()}\">$type</a></td>
				<td><a href=\"{$address->getURL()}\">$status</a></td>
				<td><a href=\"{$address->getURL()}\">$jurisdiction</a></td>
				<td><a href=\"{$address->getURL()}\">$zip</a></td>
				<td>$count</td>
			</tr>
			";
		}
		echo "</table>";
	}
	else {
		echo "<h2>No Addresses Found</h2>";
	}
echo "</div>";
