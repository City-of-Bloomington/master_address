<?php
	include(GLOBAL_INCLUDES."/xhtmlHeader.inc");
	include(APPLICATION_HOME."/includes/banner.inc");
	include(APPLICATION_HOME."/includes/menubar.inc");
	include(APPLICATION_HOME."/includes/sidebar.inc");
?>
<div id="mainContent">
	<div class="interfaceBox">
		<div class="titleBar">StatusCodes</div>
		<table>
		<?php
			require_once(APPLICATION_HOME."/classes/StatusCodeList.inc");

			$statusCodeList = new StatusCodeList();
			$statusCodeList->find();
			foreach($statusCodeList as $statusCode)
			{
				echo "<tr>";
					if ( isset($_SESSION['USER']) && in_array("Administrator",$_SESSION['USER']->getRoles()) )
					{ echo "<td><button type=\"button\" class=\"editSmall\" onclick=\"document.location.href='updateStatusCodeForm.php?statusCode={$statusCode->getStatusCode()}'\">Edit</button></td>"; }
				echo "<td>{$statusCode->getStatus()}</td></tr>";
			}
		?>
		</table>
	</div>
</div>
<?php
	include(APPLICATION_HOME."/includes/footer.inc");
	include(GLOBAL_INCLUDES."/xhtmlFooter.inc");
?>