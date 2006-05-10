<?php
	include(GLOBAL_INCLUDES."/xhtmlHeader.inc");
	include(APPLICATION_HOME."/includes/banner.inc");
	include(APPLICATION_HOME."/includes/menubar.inc");
	include(APPLICATION_HOME."/includes/sidebar.inc");
?>
<div id="mainContent">
	<div class="interfaceBox">
		<div class="titleBar">Annexations</div>
		<table>
		<?php
			require_once(APPLICATION_HOME."/classes/AnnexationList.inc");

			$annexationList = new AnnexationList();
			$annexationList->find();
			foreach($annexationList as $annexation)
			{
				echo "<tr>";
					if ( isset($_SESSION['USER']) && in_array("Administrator",$_SESSION['USER']->getRoles()) )
					{ echo "<td><button type=\"button\" class=\"editSmall\" onclick=\"document.location.href='updateAnnexationForm.php?id={$annexation->getId()}'\">Edit</button></td>"; }
				echo "
					<td>{$annexation->getOrdinanceNumber()}</td>
					<td>{$annexation->getName()}</td>
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