<div id="footer">
	<?php
		# Calculate the process time
		$endTime = microtime(1);
		$processTime = $endTime - $startTime;
		echo "<!-- Process Time: $processTime -->";
	?>
	<h1>City of Bloomington, IN</h1>
	<ul><li><a href="<?php echo BASE_URL; ?>">Home</a></li>
		<li><a href="<?php echo BASE_URL; ?>/plats">Search Plats</a></li>
		<li><a href="<?php echo BASE_URL; ?>/contacts">Contacts</a></li>
		<li>Activity Report</li>
	</ul>
</div>
