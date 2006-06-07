<?php
/*
	$_GET variables:	id
*/
	include(GLOBAL_INCLUDES."/xhtmlHeader.inc");
	include(APPLICATION_HOME."/includes/banner.inc");
	include(APPLICATION_HOME."/includes/menubar.inc");
	include(APPLICATION_HOME."/includes/sidebar.inc");

	$name = new Name($_GET['id']);
?>
<div id="mainContent">
	<h1><?php echo "{$name->getFullname()} {$name->getTown()->getName()}"; ?></h1>
	<div class="interfaceBox"><div class="titleBar">Street Names</div>
	<table>
	<tr><th>Street ID</th><th>Name Type</th><th>Status</th></tr>
	<?php
		foreach($name->getStreetNames() as $streetName)
		{
			echo "
			<tr><td><a href=\"".BASE_URL."/streets/viewStreet.php?id={$streetName->getStreet()->getId()}\">{$streetName->getStreet()->getId()}</a></td>
				<td>{$streetName->getType()}</td>
				<td>{$streetName->getStreet()->getStatus()->getStatus()}</td>
			</tr>
			";
		}
	?>
	</table>
	</div>
</div>
<?php
	include(APPLICATION_HOME."/includes/footer.inc");
	include(GLOBAL_INCLUDES."/xhtmlFooter.inc");
?>