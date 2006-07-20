<?php
	if (userHasRole("Administrator"))
	{
		$addButton = "<button type=\"button\" class=\"addSmall\" onclick=\"document.location.href='".BASE_URL."/names/addStreetName.php?name_id={$this->name->getId()}';\">Add</button>";
	}
	else { $addButton = ""; }
?>
<div class="interfaceBox">
	<div class="titleBar"><?php echo $addButton; ?>Streets</div>
<table>
<tr><th>Street ID</th><th>Name Type</th><th>Status</th><th>Main Name</tr>
<?php
	foreach($this->name->getStreetNames() as $streetName)
	{
		echo "
		<tr><td><a href=\"".BASE_URL."/streets/viewStreet.php?street_id={$streetName->getStreet()->getId()}\">{$streetName->getStreet()->getId()}</a></td>
			<td>{$streetName->getType()}</td>
			<td>{$streetName->getStreet()->getStatus()->getStatus()}</td>
			<td>{$streetName->getStreet()->getFullStreetName()}</td>
		</tr>
		";
	}
?>
</table>
</div>