<h1><?php
		if (userHasRole("Administrator"))
		{
			echo "<button type=\"button\" class=\"editSmall\" onclick=\"document.location.href='".BASE_URL."/names/updateName.php?name_id={$this->name->getId()}';\">Edit</button>";
		}
		echo "Name:{$this->name->getId()}";
	?>
</h1>
<h2><?php echo $this->name; ?></h2>
<table>
<tr><th>Town</th><td><?php echo $this->name->getTown(); ?></td></tr>
<tr><th>Dates</th><td><?php echo "{$this->name->getStartDate()} - {$this->name->getEndDate()}"; ?></td></tr>
</table>
<p class="comments"><?php echo $this->name->getNotes(); ?></p>