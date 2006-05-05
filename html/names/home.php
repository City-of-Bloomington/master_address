<?php
	include(GLOBAL_INCLUDES."/xhtmlHeader.inc");
	include(APPLICATION_HOME."/includes/banner.inc");
	include(APPLICATION_HOME."/includes/menubar.inc");
	include(APPLICATION_HOME."/includes/sidebar.inc");
?>
<div id="mainContent">
	<div class="interfaceBox">
		<div class="titleBar">Names</div>
		<table>
		<?php
			require_once(APPLICATION_HOME."/classes/NameList.inc");

			$nameList = new NameList();
			$nameList->find();
			foreach($nameList as $name)
			{
				echo "<tr>";
					if ( isset($_SESSION['USER']) && in_array("Administrator",$_SESSION['USER']->getRoles()) )
					{ echo "<td><button type=\"button\" class=\"editSmall\" onclick=\"document.location.href='updateNameForm.php?nameID={$name->getNameID()}'\">Edit</button></td>"; }
				echo "
					<td>{$name->getDirectionCode()} {$name->getName()} {$name->getSuffixAbbreviation()} {$name->getPostDirectionCode()}</td>
					<td>{$name->getStartDate()} - {$name->getEndDate()}</td>
					<td>{$name->getTown()->getName()}</td>
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