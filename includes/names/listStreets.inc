<?php
/*
	Required variables:		name
*/
?>
<div class="interfaceBox">
	<div class="titleBar">
		<?php
			if (userHasRole("Administrator"))
			{
				echo "<button type=\"button\" class=\"addSmall\"
							onclick=\"document.location.href='".BASE_URL."/streetNames/addStreetNameForm.php?name_id={$name->getId()};return_url=".BASE_URL."/names/viewName.php?id={$name->getId()}';\">
							Add
					</button>";
			}
		?>
		Streets
	</div>
<table>
<tr><th>Street ID</th><th>Name Type</th><th>Status</th><th>Main Name</tr>
<?php
	foreach($name->getStreetNames() as $streetName)
	{
		echo "
		<tr><td><a href=\"".BASE_URL."/streets/viewStreet.php?id={$streetName->getStreet()->getId()}\">{$streetName->getStreet()->getId()}</a></td>
			<td>{$streetName->getType()}</td>
			<td>{$streetName->getStreet()->getStatus()->getStatus()}</td>
			<td>{$streetName->getStreet()->getFullStreetName()}</td>
		</tr>
		";
	}
?>
</table>
</div>