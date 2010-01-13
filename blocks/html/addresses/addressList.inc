<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param AddressList $this->addressList
 * @param Street $this->street (optional)
 */
echo "
<div class=\"interfaceBox addressList\">
	<h1>Addresses</h1>
";
	if (count($this->addressList)) {
		echo "
		<table>
			<thead>
				<tr><th>#</th>
					<th>Type</th>
					<th>Street</th>
					<th>Status</th>
					<th>Jurisdiction</th>
					<th>Zip</th>
					<th>City</th>
					<th>Units</th>
				</tr>
			</thead>
			<tbody>
	";
			foreach ($this->addressList as $address) {
				$street_number = $address->getStreet_number();
				$type = $address->getAddress_type();
				$street = View::escape($address->getStreetName());
				$status = $address->getStatus();
				$jurisdiction = $address->getJurisdiction();
				$zip = $address->getZip();
				$city = View::escape($address->getCity());
				$count = $address->getSubunitCount();
				$count = $count ? $count : '';
				echo "
				<tr><td><a href=\"{$address->getURL()}\">$street_number</a></td>
					<td><a href=\"{$address->getURL()}\">$type</a></td>
					<td><a href=\"{$address->getURL()}\">$street</a></td>
					<td><a href=\"{$address->getURL()}\">$status</a></td>
					<td><a href=\"{$address->getURL()}\">$jurisdiction</a></td>
					<td><a href=\"{$address->getURL()}\">$zip</a></td>
					<td><a href=\"{$address->getURL()}\">$city</a></td>
					<td>$count</td>
				</tr>
				";
			}
		echo "
			</tbody>
		</table>
		";
	}
	else {
		echo "<h2>No Addresses Found</h2>";
	}
echo "</div>";

if (userIsAllowed('Address') && !$this->deactivateButtons) {
	$url = new URL(BASE_URL.'/addresses/addAddress.php');
	if (isset($this->street)) {
		$url->street_id = $this->street->getId();
	}
	echo "
	<p>Can't find what you're looking for?</p>
	<div>
		<button type=\"button\" onclick=\"document.location.href='$url';\">
			Add an Address
		</button>
	</div>
	";
}
