<?php
	include(GLOBAL_INCLUDES."/xhtmlHeader.inc");
	include(APPLICATION_HOME."/includes/banner.inc");
	include(APPLICATION_HOME."/includes/menubar.inc");
	include(APPLICATION_HOME."/includes/sidebar.inc");
?>
<div id="mainContent">
	<div class="interfaceBox">
		<div class="titleBar">PlatTypes</div>
		<table>
		<?php
			require_once(APPLICATION_HOME."/classes/PlatTypeList.inc");

			$platTypeList = new PlatTypeList();
			$platTypeList->find();
			foreach($platTypeList as $platType)
			{
				echo "<tr>";
					if ( isset($_SESSION['USER']) && in_array("Administrator",$_SESSION['USER']->getRoles()) )
					{ echo "<td><button type=\"button\" class=\"editSmall\" onclick=\"document.location.href='updatePlatTypeForm.php?id={$platType->getId()}'\">Edit</button></td>"; }
				echo "
					<td>{$platType->getType()}</td>
					<td>{$platType->getDescription()}</td>
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