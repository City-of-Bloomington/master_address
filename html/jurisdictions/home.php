<?php
	include(GLOBAL_INCLUDES."/xhtmlHeader.inc");
	include(APPLICATION_HOME."/includes/banner.inc");
	include(APPLICATION_HOME."/includes/menubar.inc");
	include(APPLICATION_HOME."/includes/sidebar.inc");
?>
<div id="mainContent">
	<div class="interfaceBox">
		<div class="titleBar">Jurisdictions</div>
		<table>
		<?php
			require_once(APPLICATION_HOME."/classes/JurisdictionList.inc");

			$jurisdictionList = new JurisdictionList();
			$jurisdictionList->find();
			foreach($jurisdictionList as $jurisdiction)
			{
				echo "<tr>";
					if ( isset($_SESSION['USER']) && in_array("Administrator",$_SESSION['USER']->getRoles()) )
					{ echo "<td><button type=\"button\" class=\"editSmall\" onclick=\"document.location.href='updateJurisdictionForm.php?id={$jurisdiction->getId()}'\">Edit</button></td>"; }
				echo "<td>{$jurisdiction->getName()}</td></tr>";
			}
		?>
		</table>
	</div>
</div>
<?php
	include(APPLICATION_HOME."/includes/footer.inc");
	include(GLOBAL_INCLUDES."/xhtmlFooter.inc");
?>