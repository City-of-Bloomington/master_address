<?php
/**
 * @copyright 2009-2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
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
					<th>Gov. Jurisdiction</th>
					<th>Zip</th>
					<th>City</th>
					<th>Units</th>
				</tr>
			</thead>
			<tbody>
	";
            $fields = [
                'streetNumber', 'address_type', 'streetName',
                'status', 'jurisdiction', 'zip', 'city'
            ];
			foreach ($this->addressList as $address) {
                foreach ($fields as $f) {
                    $get = 'get'.ucfirst($f);
                    $$f = self::escape($address->$get());
                }
                $url = $address->getURL();

				$count = $address->getSubunitCount();
				$count = $count ? $count : '';
				echo "
				<tr><td><a href=\"$url\">$streetNumber</a></td>
					<td><a href=\"$url\">$address_type</a></td>
					<td><a href=\"$url\">$streetName</a></td>
					<td><a href=\"$url\">$status</a></td>
					<td><a href=\"$url\">$jurisdiction</a></td>
					<td><a href=\"$url\">$zip</a></td>
					<td><a href=\"$url\">$city</a></td>
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
