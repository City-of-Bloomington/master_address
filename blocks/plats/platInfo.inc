<?php
/*
	$this->plat
*/
	$editButton = userHasRole("Administrator") ? "<button type=\"button\" class=\"editSmall\" onclick=\"document.location.href=('".BASE_URL."/plats/updatePlat.php?plat_id={$this->plat->getId()}');\">Edit</button>" : "";
?>
<div class="interfaceBox">
	<div class="titleBar">Plat: <?php echo "$editButton{$this->plat->getName()}"; ?></div>
	<table>
	<tr><th>ID</th>
		<td><?php echo $this->plat->getId(); ?></td></tr>
	<tr><th>Township</th>
		<td><?php echo $this->plat->getTownship(); ?></td></tr>
	<tr><th>Type</th>
		<td><?php echo $this->plat->getType(); ?></td></tr>
	<tr><th>Cabinet</th>
		<td><?php echo $this->plat->getCabinet(); ?></td></tr>
	<tr><th>Envelope</th>
		<td><?php echo $this->plat->getEnvelope(); ?></td></tr>
	</table>
	<p class="comments"><?php echo $this->plat->getNotes(); ?></p>
</div>