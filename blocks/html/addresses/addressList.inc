<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param AddressList $this->addressList
 */
?>
<div class="interfaceBox">
	<h1>
		<?php
			if (userHasRole('Administrator')) {
				echo "<a class=\"add button\" href=\"".BASE_URL."/addresses/addAddress.php\">Add</a>";
			}
		?>
		Addresss
	</h1>
	<table>
		<tr><th></th>
			<th>Type</th>
			<th>Street ID</th>
			<th>Jurisdiction</th>
		</tr>
		<?php
			foreach ($this->addressList as $address) {
				$editButton = '';
				if (userHasRole('Administrator')) {
					$url = new URL(BASE_URL.'/addresses/updateAddress.php');
					$url->address_id = $address->getId();
					$editButton = "<a class=\"edit button\" href=\"$url\">Edit</a>";
				}
				echo "
				<tr><td>$editButton</td>
					<td>{$address->getType()}</td>
					<td>{$address->getStreet_id()}</td>
					<td>{$address->getJurisdiction()}</td>
				</tr>
				";
			}
		?>
	</table>
</div>