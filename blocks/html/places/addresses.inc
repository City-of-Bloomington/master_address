<?php
/**
 * @copyright Copyright (C) 2006 City of Bloomington, Indiana. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 */
/*
	$this->place
*/
	$addButton = userHasRole("Administrator") ? "<button type=\"button\" class=\"addSmall\" onclick=\"document.location.href='".BASE_URL."/places/addAddress.php?place_id={$this->place->getId()}';\">Add</button>" : "";
?>
<div class="titleBar"><?php echo $addButton; ?>Addresses</div>
<table>
<tr><th>ID</th><th>Address</th><th>Status</th><th>Start Date</th><th>End Date</th></tr>
<?php
	foreach($this->place->getAddresses() as $address)
	{
		echo "
		<tr><td><a href=\"".BASE_URL."/addresses/viewAddress.php?address_id={$address->getId()}\">{$address->getId()}</a></td>
			<td><a href=\"".BASE_URL."/addresses/viewAddress.php?address_id={$address->getId()}\">{$address->getFullAddress()}</a></td>
			<td><a href=\"".BASE_URL."/addresses/viewAddress.php?address_id={$address->getId()}\">{$address->getStatus()->getStatus()}</a></td>
			<td><a href=\"".BASE_URL."/addresses/viewAddress.php?address_id={$address->getId()}\">{$address->getStartDate()}</a></td>
			<td><a href=\"".BASE_URL."/addresses/viewAddress.php?address_id={$address->getId()}\">{$address->getEndDate()}</a></td>
		</tr>
		";
	}
?>
</table>