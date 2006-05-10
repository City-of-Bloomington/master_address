<?php
/*
	$_GET variables:	id
*/
	verifyUser("Administrator");

	include(GLOBAL_INCLUDES."/xhtmlHeader.inc");
	include(APPLICATION_HOME."/includes/banner.inc");
	include(APPLICATION_HOME."/includes/menubar.inc");
	include(APPLICATION_HOME."/includes/sidebar.inc");

	require_once(APPLICATION_HOME."/classes/District.inc");
	$district = new District($_GET['id']);
?>
<div id="mainContent">
	<?php include(GLOBAL_INCLUDES."/errorMessages.inc"); ?>

	<form method="post" action="updateDistrict.php">
	<fieldset><legend>District</legend>
		<input name="id" type="hidden" value="<?php echo $_GET['id']; ?>" />

		<table>
		<tr><td><label for="name">Name</label></td>
			<td><input name="name" id="name" value="<?php echo $district->getName(); ?>" /></td></tr>
		<tr><td><label for="districtType_id">Type</label></td>
			<td><select name="districtType_id" id="districtType_id">
				<?php
					require_once(APPLICATION_HOME."/classes/DistrictTypeList.inc");
					$list = new DistrictTypeList();
					$list->find();
					foreach($list as $type)
					{
						if ($type->getId() != $district->getDistrictType_id()) { echo "<option value=\"{$type->getId()}\">{$type->getType()}</option>"; }
						else { echo "<option value=\"{$type->getId()}\" selected=\"selected\">{$type->getType()}</option>"; }
					}
				?>
				</select></td></tr>
		</table>

		<button type="submit" class="submit">Submit</button>
		<button type="button" class="cancel" onclick="document.location.href='home.php';">Cancel</button>
	</fieldset>
	</form>
</div>
<?php
	include(APPLICATION_HOME."/includes/footer.inc");
	include(GLOBAL_INCLUDES."/xhtmlFooter.inc");
?>