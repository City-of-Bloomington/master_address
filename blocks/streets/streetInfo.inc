<h1><?php
		if (userHasRole("Administrator"))
		{
			echo "<button type=\"button\" class=\"editSmall\" onclick=\"document.location.href='".BASE_URL."/streets/updateStreet.php?street_id={$this->street->getId()};return_url={$this->response->getURL()}';\">Edit</button>";
		}
		echo "Street:{$this->street->getId()}";
	?>
</h1>
<table>
<tr><th>Status</th><td><?php echo $this->street->getStatus(); ?></td></tr>
</table>
<p class="comments"><?php echo $this->street->getNotes(); ?></p>