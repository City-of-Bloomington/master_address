<?php
/*
	$this->unit
*/
	$editButton = userHasRole("Administrator") ? "<button type=\"button\" class=\"editSmall\" onclick=\"document.location.href='".BASE_URL."/units/updateUnit.php?unit_id={$this->unit->getId()}';\">Edit</button>" : "";
?>
<div class="interfaceBox">
	<div class="titleBar"><?php echo "{$editButton}Unit: {$this->unit->getId()}"; ?></div>
	<table>
	<tr><th>Type</th><td><?php echo $this->unit->getType(); ?></td></tr>
	<tr><th>Identifier</th><td><?php echo $this->unit->getIdentifier(); ?></td></tr>
	<tr><th>Mailable</th><td><?php echo $this->unit->getMailable(); ?></td></tr>
	<tr><th>Livable</th><td><?php echo $this->unit->getLivable(); ?></td></tr>
	<tr><th>Status</th><td><?php echo $this->unit->getStatus(); ?></td></tr>
	</table>
	<p class="comments"><?php echo $this->unit->getNotes(); ?><p>
</div>