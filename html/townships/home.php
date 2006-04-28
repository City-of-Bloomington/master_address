<?php
	include(GLOBAL_INCLUDES."/xhtmlHeader.inc");
	include(APPLICATION_HOME."/includes/banner.inc");
	include(APPLICATION_HOME."/includes/menubar.inc");
	include(APPLICATION_HOME."/includes/sidebar.inc");
?>
<div id="mainContent">
	<div class="interfaceBox">
		<div class="titleBar">Townships</div>
		<table>
		<?php
			require_once(APPLICATION_HOME."/classes/TownshipList.inc");

			$townshipList = new TownshipList();
			$townshipList->find();
			foreach($townshipList as $township)
			{
				echo "<tr>";
					if ( isset($_SESSION['USER']) && in_array("Administrator",$_SESSION['USER']->getRoles()) )
					{ echo "<td><button type=\"button\" class=\"editSmall\" onclick=\"document.location.href='updateTownshipForm.php?townshipID={$township->getTownshipID()}'\">Edit</button></td>"; }
				echo "
					<td>{$township->getName()} ({$township->getAbbreviation()})</td>
					<td>{$township->getQuarterCode()}</td>
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