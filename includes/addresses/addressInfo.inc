<?php
/*
	Required variables:	$address
*/
?>
	<div class="titleBar"><?php echo "{$address->getFullAddress()}, {$address->getCity()->getName()}, {$address->getZip()}"; ?></div>
	<p class="comments"><?php echo $address->getNotes(); ?></p>
	<table>
	<tr><th>Status</th><td><?php echo $address->getStatus()->getStatus(); ?></td></tr>
	<tr><th>Active</th><td><?php echO $address->getActive(); ?></td></tr>
	<tr><th>Start Date</th><td><?php echo $address->getStartDate(); ?></td></tr>
	<tr><th>End Date</th><td><?php echo $address->getEndDate(); ?></td></tr>
	</table>
