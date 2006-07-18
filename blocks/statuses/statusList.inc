<div class="interfaceBox">
	<div class="titleBar">Status Codes</div>
	<table>
	<?php
		$statusList = new StatusList();
		$statusList->find();
		foreach($statusList as $status)
		{
			echo "<tr>";
				if (userHasRole("Administrator"))
					{ echo "<td><button type=\"button\" class=\"editSmall\" onclick=\"document.location.href='".BASE_URL."/statuses/updateStatus.php?id={$status->getId()}'\">Edit</button></td>"; }
			echo "<td>{$status->getStatus()}</td></tr>";
		}
	?>
	</table>
</div>