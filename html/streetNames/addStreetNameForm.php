<?php
/*
	$_GET variables:	name_id
						street_id
						return_url		# This is where we want to go once
										# the new street name is added
*/
	verifyUser("Administrator");

	include(GLOBAL_INCLUDES."/xhtmlHeader.inc");
	include(APPLICATION_HOME."/includes/banner.inc");
	include(APPLICATION_HOME."/includes/menubar.inc");
	include(APPLICATION_HOME."/includes/sidebar.inc");

	if (isset($_GET['name_id'])) { $name = new Name($_GET['name_id']); }
	if (isset($_GET['street_id'])) { $street = new Street($_GET['street_id']); }

	# URLs need to get stored in the session to avoid all the urlencode recursion problems
	if (isset($_GET['return_url'])) { $_SESSION['return_url'] = $_GET['return_url']; }
	$_SESSION['error_url'] = $_SERVER['REQUEST_URI'];
?>
<div id="mainContent">
	<h1>Associate a new Street Name</h1>
	<?php include(GLOBAL_INCLUDES."/errorMessages.inc"); ?>

	<div class="interfaceBox"><div class="titleBar">Name</div>
	<?php
		#----------------------------------------------------------------------
		# Gathe the Name information
		#----------------------------------------------------------------------
		$return_url = "$_SERVER[PHP_SELF]?";
		if (isset($street)) { $return_url.="street_id={$street->getId()};"; }
		$return_url.="name_id=";

		if (isset($name))
		{
			echo "<h2>Use this name:</h2><input name=\"name_id\" type=\"hidden\" value=\"{$name->getId()}\" />";
			include(APPLICATION_HOME."/includes/names/nameInfo.inc");
		}
		else
		{
			include(APPLICATION_HOME."/includes/names/addNameForm.inc");
			include(APPLICATION_HOME."/includes/names/findNameForm.inc");
		}
	?>
	</div>
	<div class="interfaceBox"><div class="titleBar">Street</div>
	<?php
		#----------------------------------------------------------------------
		# Gather the Street information
		#----------------------------------------------------------------------
		$return_url = "$_SERVER[PHP_SELF]?";
		if (isset($name)) { $return_url.="name_id={$name->getId()};"; }
		$return_url.="street_id=";

		if (isset($street))
		{
			echo "<h2>Use this street:</h2><input name=\"street_id\" type=\"hidden\" value=\"{$street->getId()}\" />";
			include(APPLICATION_HOME."/includes/streets/streetInfo.inc");
		}
		else
		{
			include(APPLICATION_HOME."/includes/streets/addStreetForm.inc");
			include(APPLICATION_HOME."/includes/streets/searchForm.inc");
		}
	?>
	</div>
	<?php
		#----------------------------------------------------------------------
		# If we've got all the information, go ahead and show the real form
		#----------------------------------------------------------------------
		if (isset($name) && isset($street))
		{
			echo "
			<div class=\"interfaceBox\"><div class=\"titleBar\">New Street Name</div>
			<form method=\"post\" action=\"addStreetName.php\">
			<fieldset><legend>Street Name Info</legend>
				<input name=\"name_id\" type=\"hidden\" value=\"{$name->getId()}\" />
				<input name=\"street_id\" type=\"hidden\" value=\"{$street->getId()}\" />
				<div><label>Type
							<select name=\"streetNameType_id\" id=\"streetNameType_id\">
			";
							$types = new StreetNameTypeList();
							$types->find();
							foreach($types as $type) { echo "<option value=\"{$type->getId()}\">{$type->getType()}</option>"; }
			echo "
						</select>
				</label></div>
				<button type=\"submit\">Submit</button>
			</fieldset>
			</form>
			</div>
			";
		}
	?>
</div>
<?php
	include(APPLICATION_HOME."/includes/footer.inc");
	include(GLOBAL_INCLUDES."/xhtmlFooter.inc");
?>