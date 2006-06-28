<?php
/*
	Required variables:		$name
*/
?>
<h1><?php
		if (userHasRole("Administrator")) { echo "<button type=\"button\" class=\"editSmall\" onclick=\"document.location.href='updateNameForm.php?id={$name->getId()}';\">Edit</button>"; }
		echo "Name:{$name->getId()}";
	?>
</h1>
<h2><?php echo $name; ?></h2>
<table>
<tr><th>Town</th><td><?php echo $name->getTown(); ?></td></tr>
<tr><th>Dates</th><td><?php echo "{$name->getStartDate()} - {$name->getEndDate()}"; ?></td></tr>
</table>
<p class="comments"><?php echo $name->getNotes(); ?></p>
