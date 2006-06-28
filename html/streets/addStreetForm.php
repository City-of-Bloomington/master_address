<?php
/*
	$_GET variables:	name_id
*/
	verifyUser("Administrator");

	# We have to have a name already, in order to add a street
	if (!$_GET['name_id'])
	{
		$_SESSION['errorMessages'][] = new Exception("nameRequired");
		Header("Location: BASE_URL/names/findNameForm.php");
		exit();
	}

	include(GLOBAL_INCLUDES."/xhtmlHeader.inc");
	include(APPLICATION_HOME."/includes/banner.inc");
	include(APPLICATION_HOME."/includes/menubar.inc");
	include(APPLICATION_HOME."/includes/sidebar.inc");
?>
<div id="mainContent">
	<?php
		include(GLOBAL_INCLUDES."/errorMessages.inc");
		$name = new Name($PDO,$_GET['name_id']);
	?>
	<h1>Add a new Street</h1>
	<form method="post" action="addStreet.php">
	<fieldset><legend>Name Info</legend>
		<input name="name_id" type="hidden" value="<?php echo $_GET['name_id']; ?>" />
		<table>
		<tr><th>Name</th><td><?php echo $name; ?></td></tr>
		<tr><th>Town</th><td><?php echo $name->getTown(); ?></td></tr>
		<tr><th>Dates</th>
			<td><?php echo "{$name->getStartDate()} - {$name->getEndDate()}"; ?></td></tr>
		</table>
		<p class="comments"><?php echo $name->getNotes(); ?></p>
	</fieldset>

	<fieldset><legend>Street Name Info</legend>
		<label>Type
				<select name="streetNameType_id" id="streetNameType_id">
				<?php
					$types = new StreetNameTypeList($PDO);
					$types->find();
					foreach($types as $type) { echo "<option value=\"{$type->getId()}\">{$type->getType()}</option>"; }
				?>
				</select>
		</label>
	</fieldset>

	<fieldset><legend>Street Info</legend>
		<label>Status
				<select name="status_id" id="status_id">
				<?php
					$statusList = new StatusList($PDO);
					$statusList->find();
					foreach($statusList as $status) { echo "<option value=\"{$status->getId()}\">{$status->getStatus()}</option>"; }
				?>
				</select>
		</label>
		<div><label for="street_notes">Notes</label></div>
		<div><textarea name="street_notes" id="street_notes" rows="3" cols="60"></textarea></div>
	</fieldset>

	<fieldset>
		<button type="submit">Submit</button>
	</fieldset>
	</form>

</div>
<?php
	include(APPLICATION_HOME."/includes/footer.inc");
	include(GLOBAL_INCLUDES."/xhtmlFooter.inc");
?>