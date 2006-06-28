<?php
/*
	Required Variables:		street
							return_url
*/
?>
<div class="interfaceBox">
	<div class="titleBar">
		<?php
			if (userHasRole("Administrator"))
			{
				echo "<button type=\"button\" class=\"addSmall\" onclick=\"document.location.href='".BASE_URL."/streetNames/addStreetNameForm.php?street_id={$street->getId()};return_url=$return_url';\">Add</button>";
			}
		?>
		Street Names
	</div>
	<table>
	<tr><th></th><th>Name</th><th>Type</th>
	<?php
		foreach($street->getStreetNames() as $streetName)
		{
			if (userHasRole("Administrator"))
			{
				echo "<tr><td><button type=\"button\" class=\"editSmall\" onclick=\"document.location.href='".BASE_URL."/streetNames/updateStreetName.php?id={$streetName->getId()};return_url=$return_url';\">Edit</button></td>";
			}
			else { echo "<tr><td></td>"; }
			echo "
				<td><a href=\"".BASE_URL."/names/viewName.php?id={$streetName->getName()->getId()}\">{$streetName->getFullname()}</a></td>
				<td>{$streetName->getType()}</td></tr>
			";
		}
	?>
	</table>
</div>