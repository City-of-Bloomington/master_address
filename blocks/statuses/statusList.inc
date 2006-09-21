<?php
/*
	The block that is including this file needs to set:
	$type
	$statusList
*/
	if (userHasRole("Administrator")) { $addButton = "<button type=\"button\" class=\"addSmall\" onclick=\"document.location.href='".BASE_URL."/statuses/addStatus.php?type=$type'\">Add</button></td>"; }
	else { $addButton = ""; }
?>
<div class="interfaceBox">
	<div class="titleBar"><?php echo $addButton.ucfirst($type); ?> Status Codes</div>
	<table>
	<?php
		foreach($statusList as $status)
		{
			echo "<tr>";
				if (userHasRole("Administrator"))
				{ echo "<td><button type=\"button\" class=\"editSmall\" onclick=\"document.location.href='".BASE_URL."/statuses/updateStatus.php?type=$type;id={$status->getId()}'\">Edit</button></td>"; }
			echo "<td>{$status->getStatus()}</td></tr>";
		}
	?>
	</table>
</div>