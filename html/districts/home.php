<?php
	include(GLOBAL_INCLUDES."/xhtmlHeader.inc");
	include(APPLICATION_HOME."/includes/banner.inc");
	include(APPLICATION_HOME."/includes/menubar.inc");
	include(APPLICATION_HOME."/includes/sidebar.inc");
?>
<div id="mainContent">
	<div class="interfaceBox">
		<div class="titleBar">Districts</div>
		<table>
		<?php
			$districtList = new DistrictList($PDO);
			$districtList->find();
			foreach($districtList as $district)
			{
				echo "<tr>";
					if ( isset($_SESSION['USER']) && in_array("Administrator",$_SESSION['USER']->getRoles()) )
					{ echo "<td><button type=\"button\" class=\"editSmall\" onclick=\"document.location.href='updateDistrictForm.php?id={$district->getId()}'\">Edit</button></td>"; }
				echo "
					<td><a href=\"viewDistrict.php?id={$district->getId()}\">{$district->getName()}</a></td>
					<td><a href=\"viewDistrict.php?id={$district->getId()}\">{$district->getDistrictType()->getType()}</a></td>
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