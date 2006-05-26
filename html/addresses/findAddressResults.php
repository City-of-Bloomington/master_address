<?php
/*
					# Address Info
	$_GET variables:	number				addressType		startMonth
						suffix				city_id			startDay
											zip  zipplus4	startYear
											active			endMonth
															endDay
											notes			endYear
					# Street Info
						direction_id
						name
						suffix_id
						postDirection_id
*/
	include(GLOBAL_INCLUDES."/xhtmlHeader.inc");
	include(APPLICATION_HOME."/includes/banner.inc");
	include(APPLICATION_HOME."/includes/menubar.inc");
	include(APPLICATION_HOME."/includes/sidebar.inc");
?>
<div id="mainContent">
	<h1>Addresses Found</h1>
	<?php
		$fields = array();
		foreach($_GET as $name=>$value) { if ($value) { $fields[$name] = $value; } }

		$addressList = new AddressList();
		$addressList->find($fields);

		if (count($addressList))
		{
			echo "<table>";

			if (!isset($_GET['page'])) { $_GET['page'] = 0; }
			$pages = $addressList->getPagination(50);
			$page = new LimitIterator($addressList->getIterator(),$pages[$_GET['page']],$pages->getPageSize());
			foreach($page as $address)
			{
				echo "
				<tr><td><a href=\"viewAddress.php?id={$address->getId()}\">{$address->getNumber()} {$address->getSuffix()}</a></td>
					<td><a href=\"viewAddress.php?id={$address->getId()}\">{$address->getSegment()->getFullStreetName()}</a></td>
				</tr>
				";
			}

			echo "</table>";

			include(APPLICATION_HOME."/includes/pageNavigation.inc");
		}
	?>
</div>
<?php
	include(APPLICATION_HOME."/includes/footer.inc");
	include(GLOBAL_INCLUDES."/xhtmlFooter.inc");
?>