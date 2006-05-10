<div class="menuBar">
	<a href="<?php echo BASE_URL; ?>">Home</a>
	<?php if (isset($_SESSION['USER'])) { echo "<a href=\"".BASE_URL."/logout.php\">Logout</a>"; } ?>
</div>
