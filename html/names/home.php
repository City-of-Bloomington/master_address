<?php
	Header("Location: findNameForm.php");
/*
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
			$nameList = new NameList($PDO);
			$nameList->find();


			if (!isset($_GET['page'])) { $_GET['page'] = 0; }
			$pages = $nameList->getPagination(50);


			$iterator = new LimitIterator($nameList->getIterator(),$pages[$_GET['page']],$pages->getPageSize());
			foreach($iterator as $i=>$name)
			{
				$fullname = "";
				if ($name->getDirection_id()) { $fullname .= $name->getDirection()->getCode(); }
				$fullname.=" {$name->getName()} ";
				if ($name->getSuffix_id()) { $fullname .= $name->getSuffix()->getSuffix(); }
				if ($name->getPostDirection_id()) { $fullname .= " ".$name->getPostDirection()->getCode(); }

				$town = $name->getTown_id() ? $name->getTown()->getName() : "";

				echo "<tr>";
					if ( isset($_SESSION['USER']) && in_array("Administrator",$_SESSION['USER']->getRoles()) )
					{ echo "<td><button type=\"button\" class=\"editSmall\" onclick=\"document.location.href='updateNameForm.php?id={$name->getId()}'\">Edit</button></td>"; }
				echo "
					<th>$i</th>
					<td>$fullname</td>
					<td>{$name->getStartDate()} - {$name->getEndDate()}</td>
					<td>$town</td>
				</tr>
				";
			}
		?>
		</table>

		<?php include(GLOBAL_INCLUDES."/pageNavigation.inc"); ?>
	</div>
</div>
<?php
	include(APPLICATION_HOME."/includes/footer.inc");
	include(GLOBAL_INCLUDES."/xhtmlFooter.inc");
*/
?>