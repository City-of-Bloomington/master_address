<div class="sidebar">
	<div class="titleBar">Addresses</div>
	<ul>
		<li><a href="<?php echo BASE_URL; ?>/searchForm.php">Search Addresses</a></li>
	</ul>

</div>
<?php
	if (!isset($_SESSION['USER'])) { include(GLOBAL_INCLUDES."/sidebarBoxes/loginBox.inc"); }
	else
	{
		if (in_array("Administrator",$_SESSION['USER']->getRoles())) { include(APPLICATION_HOME."/includes/sidebarBoxes/Administrator.inc"); }
	}
?>