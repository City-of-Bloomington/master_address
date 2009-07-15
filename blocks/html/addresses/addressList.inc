<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param AddressList $this->addressList
 */
echo $this->addressList->getSQL();
if (count($this->addressList)) {
	$addButton = userHasRole('Administrator')
				? "<a class=\"add button\" href=\"".BASE_URL."/addresses/addAddress.php\">Add</a>"
				: '';
	echo "
<div class=\"interfaceBox\">
	<h1>$addButton Addresss</h1>
	<table>
		<tr><th></th>
			<th>ID</th>
			<th>Address</th>
		</tr>
	";
			foreach ($this->addressList as $address) {
				$editButton = '';
				if (userHasRole('Administrator')) {
					$url = new URL(BASE_URL.'/addresses/updateAddress.php');
					$url->street_address_id = $address->getId();
					$editButton = "<a class=\"edit button\" href=\"$url\">Edit</a>";
				}
				$streetAddress = View::escape($address->getStreetAddress());
				echo "
				<tr><td>$editButton</td>
					<td><a href=\"{$address->getURL()}\">{$address->getId()}</a></td>
					<td><a href=\"{$address->getURL()}\">$streetAddress</a></td>
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
