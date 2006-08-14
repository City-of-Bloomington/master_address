<?php
/*
	$this->street
	$this->response
*/
?>
<div class="interfaceBox">
	<div class="titleBar">
		<?php
			if (userHasRole("Administrator"))
			{
				echo "<button type=\"button\" class=\"addSmall\" onclick=\"document.location.href='".BASE_URL."/streets/addStreetName.php?street_id={$this->street->getId()};return_url=$this->return_url';\">Add</button>";
			}
		?>
		Street Names
	</div>
	<table>
	<tr><th></th><th>Name</th><th>Type</th>
	<?php
		foreach($this->street->getStreetNames() as $streetName)
		{
			if (userHasRole("Administrator"))
			{
				echo "<tr><td><button type=\"button\" class=\"editSmall\" onclick=\"document.location.href='".BASE_URL."/streetNames/updateStreetName.php?streetName_id={$streetName->getId()};return_url=$_SERVER[REQUEST_URI]';\">Edit</button></td>";
			}
			else { echo "<tr><td></td>"; }
			echo "
				<td><a href=\"".BASE_URL."/names/viewName.php?name_id={$streetName->getName()->getId()}\">{$streetName->getFullname()}</a></td>
				<td>{$streetName->getType()}</td></tr>
			";
		}
	?>
	</table>
</div>