<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Street $this->street
 */
echo "
<div class=\"interfaceBox addressList\">
	<h1>Re-number Addresses</h1>
	<table>
		<tr><th>New #</th>
			<th>Old #</th>
			<th>Type</th>
			<th>Street</th>
			<th>Status</th>
			<th>Gov. Jurisdiction</th>
			<th>Zip</th>
			<th>Units</th>
		</tr>
	";
	$tabindex = 100;
	foreach ($this->street->getAddresses(array('status'=>'CURRENT')) as $address) {
		$street_number = $address->getStreet_number();
		$type = $address->getAddress_type();
		$street = View::escape($address->getStreetName());
		$status = $address->getStatus();
		$jurisdiction = $address->getJurisdiction();
		$zip = $address->getZip();
		$count = $address->getSubunitCount();
		$count = $count ? $count : '';
		echo "
		<tr><td><input name=\"addresses[{$address->getId()}]\" value=\"$street_number\"
					tabindex=\"$tabindex\" size=\"5\" />
			</td>
			<td><a href=\"{$address->getURL()}\">$street_number</a></td>
			<td><a href=\"{$address->getURL()}\">$type</a></td>
			<td><a href=\"{$address->getURL()}\">$street</a></td>
			<td><a href=\"{$address->getURL()}\">$status</a></td>
			<td><a href=\"{$address->getURL()}\">$jurisdiction</a></td>
			<td><a href=\"{$address->getURL()}\">$zip</a></td>
			<td>$count</td>
		</tr>
		";
		$tabindex++;
	}
echo "
	</table>
</div>";
