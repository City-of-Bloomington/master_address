<div class="titleBar">
	<?php
		$editButton = userHasRole("Administrator") ? "<button type=\"button\" class=\"editSmall\" onclick=\"document.location.href='".BASE_URL."/addresses/updateAddress.php?address_id={$this->address->getId()}';\">Edit</button>" : "";
		echo "
			$editButton
			{$this->address->getFullAddress()},
			{$this->address->getCity()->getName()},
			{$this->address->getZip()}
		";
	?>
</div>
<p class="comments"><?php echo $this->address->getNotes(); ?></p>
<table>
<tr><th>Status</th><td><?php echo $this->address->getStatus()->getStatus(); ?></td></tr>
<tr><th>Active</th><td><?php echO $this->address->getActive(); ?></td></tr>
<tr><th>Start Date</th><td><?php echo $this->address->getStartDate(); ?></td></tr>
<tr><th>End Date</th><td><?php echo $this->address->getEndDate(); ?></td></tr>
</table>