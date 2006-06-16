<?php
/*
	Required variables:	$address
*/
?>
	<div class="titleBar">
		<a href="<?php echo BASE_URL; ?>/addresses/viewAddress.php?id=<?php echo $address->getId(); ?>">
		<?php echo "{$address->getFullAddress()}, {$address->getCity()->getName()}, {$address->getZip()}"; ?></a>
	</div>
	<p class="comments"><?php echo $address->getNotes(); ?></p>
	<table>
	<tr><th>Status</th><td><?php echo $address->getStatus()->getStatus(); ?></td></tr>
	<tr><th>Active</th><td><?php echO $address->getActive(); ?></td></tr>
	<tr><th>Start Date</th><td><?php echo $address->getStartDate(); ?></td></tr>
	<tr><th>End Date</th><td><?php echo $address->getEndDate(); ?></td></tr>
	</table>
