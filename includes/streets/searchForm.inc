<?php
/*
	$_GET variables:	fullname
*/
?>
<form method="get" action="home.php">
<fieldset><legend>Find a Street</legend>
	<input name="fullname" id="fullname" />
	<button type="submit" class="search">Search</button>
</fields>
</form>

<?php
	if (isset($_GET['fullname']))
	{
		echo "<table>";
		$streetList = new StreetList();
		$streetList->find(array("fullname"=>$_GET['fullname']));
		foreach($streetList as $street)
		{
			echo "<tr>";
				if ( isset($_SESSION['USER']) && in_array("Administrator",$_SESSION['USER']->getRoles()) )
				{ echo "<td><button type=\"button\" class=\"editSmall\" onclick=\"document.location.href='updateStreetForm.php?id={$street->getId()}'\">Edit</button></td>"; }
			echo "<td>";
			echo $street->getStreetName()->getName()->getFullname();
			echo "</td></tr>";
		}
		echo "</table>";
	}
?>


