<?php
/*
	$_GET variables:	id
*/
?>
	<div class="interfaceBox">
		<div class="titleBar">
			<?php
				if (userHasRole("Administrator"))
				{ echo "<button type=\"button\" class=\"addSmall\" onclick=\"document.location.href='".BASE_URL."/streets/addStreetForm.php?name_id=$_GET[id]';\">Add</button>"; }
			?>
			Streets
		</div>
	<table>
	<tr><th>Street ID</th><th>Name Type</th><th>Status</th></tr>
	<?php
		foreach($name->getStreetNames() as $streetName)
		{
			echo "
			<tr><td><a href=\"".BASE_URL."/streets/viewStreet.php?id={$streetName->getStreet()->getId()}\">{$streetName->getStreet()->getId()}</a></td>
				<td>{$streetName->getType()}</td>
				<td>{$streetName->getStreet()->getStatus()->getStatus()}</td>
			</tr>
			";
		}
	?>
	</table>
	</div>
