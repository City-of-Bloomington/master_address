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
	<h1><?php
			if (userHasRole("Administrator")) { echo "<button type=\"button\" class=\"editSmall\" onclick=\"document.location.href='updateNameForm.php?id=$_GET[id]';\">Edit</button>"; }
			echo "Name:$_GET[id]";
		?>
	</h1>
	<h2><?php echo $name; ?></h2>
	<table>
	<tr><th>Town</th><td><?php echo $name->getTown(); ?></td></tr>
	<tr><th>Dates</th><td><?php echo "{$name->getStartDate()} - {$name->getEndDate()}"; ?></td></tr>
	</table>
	<p class="comments"><?php echo $name->getNotes(); ?></p>
	<?php include(APPLICATION_HOME."/includes/names/listStreets.inc"); ?>
</div>
<?php
	include(APPLICATION_HOME."/includes/footer.inc");
	include(GLOBAL_INCLUDES."/xhtmlFooter.inc");
?>