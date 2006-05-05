<?php
	include(GLOBAL_INCLUDES."/xhtmlHeader.inc");
	include(APPLICATION_HOME."/includes/banner.inc");
	include(APPLICATION_HOME."/includes/menubar.inc");
	include(APPLICATION_HOME."/includes/sidebar.inc");
?>
<div id="mainContent">
	<div class="interfaceBox">
		<div class="titleBar">Plats</div>
		<table>
		<?php
			require_once(APPLICATION_HOME."/classes/PlatList.inc");

			$platList = new PlatList();
			$platList->find();
			foreach($platList as $plat)
			{
				echo "<tr>";
					if ( isset($_SESSION['USER']) && in_array("Administrator",$_SESSION['USER']->getRoles()) )
					{ echo "<td><button type=\"button\" class=\"editSmall\" onclick=\"document.location.href='updatePlatForm.php?platID={$plat->getPlatID()}'\">Edit</button></td>"; }
				echo "
					<td>{$plat->getName()}</td>
					<td>{$plat->getTownship()->getAbbreviation()}</td>
					<td>{$plat->getPlatType()->getDescription()}</td>
					<td>{$plat->getCabinet()}</td>
					<td>{$plat->getEnvelope()}</td>
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