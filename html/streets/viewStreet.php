<?php
/*
	$_GET variables:	id
*/
	include(GLOBAL_INCLUDES."/xhtmlHeader.inc");
	include(APPLICATION_HOME."/includes/banner.inc");
	include(APPLICATION_HOME."/includes/menubar.inc");
	include(APPLICATION_HOME."/includes/sidebar.inc");

?>
<div id="mainContent">
	<?php
		$street = new Street($PDO,$_GET['id']);
		$return_url = "viewStreet.php?id=";
		include(APPLICATION_HOME."/includes/streets/streetInfo.inc");
		include(APPLICATION_HOME."/includes/streets/streetNames.inc");
		include(APPLICATION_HOME."/includes/streets/segments.inc");
	?>
</div>
<?php
	include(APPLICATION_HOME."/includes/footer.inc");
	include(GLOBAL_INCLUDES."/xhtmlFooter.inc");
?>