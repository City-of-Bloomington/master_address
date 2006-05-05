<div class="sidebar">
	<div class="titleBar">Tables</div>
	<ul>
		<li><a href="<?php echo BASE_URL; ?>/towns">Towns</a></li>
		<li><a href="<?php echo BASE_URL; ?>/townships">Townships</a></li>
		<li><a href="<?php echo BASE_URL; ?>/jurisdictions">Jurisdictions</a></li>
		<li><a href="<?php echo BASE_URL; ?>/statusCodes">Status Codes</a></li>
		<li><a href="<?php echo BASE_URL; ?>/suffixes">Suffixes</a></li>
		<li><a href="<?php echo BASE_URL; ?>/districtTypes">District Types</a></li>
		<li><a href="<?php echo BASE_URL; ?>/districts">Districts</a></li>
		<li><a href="<?php echo BASE_URL; ?>/placeTypes">Place Types</a></li>
		<li><a href="<?php echo BASE_URL; ?>/unitTypes">Unit Types</a></li>
		<li><a href="<?php echo BASE_URL; ?>/buildingTypes">Building Types</a></li>
		<li><a href="<?php echo BASE_URL; ?>/directions">Directions</a></li>
		<li><a href="<?php echo BASE_URL; ?>/annexations">Annexations</a></li>
		<li><a href="<?php echo BASE_URL; ?>/platTypes">Plat Types</a></li>
		<li><a href="<?php echo BASE_URL; ?>/plats">Plats</a></li>
		<li><a href="<?php echo BASE_URL; ?>/names">Names</a></li>
	</ul>

</div>
<?php
	if (!isset($_SESSION['USER'])) { include(GLOBAL_INCLUDES."/sidebarBoxes/loginBox.inc"); }
	else
	{
		if (in_array("Administrator",$_SESSION['USER']->getRoles())) { include(APPLICATION_HOME."/includes/sidebarBoxes/Administrator.inc"); }
	}
?>