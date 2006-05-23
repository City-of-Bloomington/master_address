<?php
	include(GLOBAL_INCLUDES."/xhtmlHeader.inc");
	include(APPLICATION_HOME."/includes/banner.inc");
	include(APPLICATION_HOME."/includes/menubar.inc");
	include(APPLICATION_HOME."/includes/sidebar.inc");
?>
<div id="mainContent">
	<div class="interfaceBox">
		<div class="titleBar">Streets</div>
		<table>
		<?php
			require_once(APPLICATION_HOME."/classes/StreetList.inc");

			$streetList = new StreetList();
			$streetList->find();

			if (!isset($_GET['page'])) { $_GET['page'] = 0; }
			$pages = $streetList->getPagination(50);
			$iterator = new LimitIterator($streetList->getIterator(),$pages[$_GET['page']],$pages->getPageSize());

			foreach($iterator as $street)
			{
				echo "<tr>";
					if ( isset($_SESSION['USER']) && in_array("Administrator",$_SESSION['USER']->getRoles()) )
					{ echo "<td><button type=\"button\" class=\"editSmall\" onclick=\"document.location.href='updateStreetForm.php?id={$street->getId()}'\">Edit</button></td>"; }
				echo "<td>";
				echo $street->getStreetName()->getName()->getFullname();
				echo "</td></tr>";
			}

		?>
		</table>

		<?php include(APPLICATION_HOME."/includes/pageNavigation.inc"); ?>
	</div>
</div>
<?php
	include(APPLICATION_HOME."/includes/footer.inc");
	include(GLOBAL_INCLUDES."/xhtmlFooter.inc");
?>