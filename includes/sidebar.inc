<?php
/*
	Show any tools for the current section up top
*/
?>
<div id="sidebar">
	<div><div class="titleBar">Tools</div>
		<ul>
			<li><a href="<?php echo BASE_URL; ?>/addresses">Addresses</a></li>
			<li><a href="<?php echo BASE_URL; ?>/streets">Streets</a></li>
			<li><a href="<?php echo BASE_URL; ?>/districts">Districts</a></li>
			<li><a href="<?php echo BASE_URL; ?>/places">Places</a></li>
			<li><a href="<?php echo BASE_URL; ?>/names">Names</a></li>
			<li><a href="<?php echo BASE_URL; ?>/annexations">Annexations</a></li>
			<li><a href="<?php echo BASE_URL; ?>/plats">Plats</a></li>
			<li><a href="<?php echo BASE_URL; ?>/buildings">Buildings</a></li>
		</ul>
	</div>
<?php
	if (!isset($_SESSION['USER'])) { include(FRAMEWORK."/sidebarBoxes/loginBox.inc"); }
	else { if (in_array("Administrator",$_SESSION['USER']->getRoles())) { include(APPLICATION_HOME."/includes/sidebarBoxes/Administrator.inc"); } }
?>
</div>