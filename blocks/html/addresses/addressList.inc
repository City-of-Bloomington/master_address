<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param AddressList $this->addressList
 */
if (count($this->addressList)) {
	$street_id = isset($this->street) ? $this->street->getId() : '';
	$addButton = userIsAllowed('Address')
				? "<a class=\"add button\" href=\"".BASE_URL."/addresses/addAddress.php?street_id=$street_id\">Add</a>"
				: '';
	echo "
	<div class=\"interfaceBox\">
		<h1>$addButton Addresss</h1>
		<table>
			<tr><th></th>
				<th>ID</th>
				<th>Street Number</th>
				<th>Type</th>
				<th>Status</th>
				<th>Jurisdiction</th>
				<th>Zip Code</th>
				<th>Sub Units</th>
			</tr>
	";
		foreach ($this->addressList as $address) {
			$editButton = '';
			if (userIsAllowed('Address')) {
				$url = new URL(BASE_URL.'/addresses/updateAddress.php');
				$url->street_address_id = $address->getId();
				$editButton = "<a class=\"edit button\" href=\"$url\">Edit</a>";
			}
			$streetAddress = View::escape($address->getStreetAddress());
			$street_number = $address->getStreet_number();
			$type = $address->getAddress_type();
			$status = $address->getStatus();
			$jurisdiction = $address->getJurisdiction();
			$zip = $address->getZip();
			$count = $address->getSubunitCount();
			echo "
			<tr><td>$editButton</td>
				<td><a href=\"{$address->getURL()}\">{$address->getId()}</a></td>
				<td>$street_number</td>
				<td>$type</td>
				<td>$status</td>
				<td>$jurisdiction</td>
				<td>$zip</td>
				<td>$count</td>
			</tr>
			";
		}
	echo "
		</table>
	</div>
	";
}
else {
	echo "<h2>No Addresses Found</h2>";
}
