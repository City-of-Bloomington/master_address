<?php
/*
	Required variables:		street
							return_url
*/
?>
<h1><?php
		if (userHasRole("Administrator"))
		{
			echo "<button type=\"button\" class=\"editSmall\" onclick=\"document.location.href='".BASE_URL."/streets/updateStreetForm.php?id={$street->getId()};return_url=$return_url';\">Edit</button>";
		}
		echo "Street:{$street->getId()}";
	?>
</h1>
<table>
	<tr><th>Status</th><td><?php echo $street->getStatus(); ?></td></tr>
</table>
<p class="comments"><?php echo $street->getNotes(); ?></p>