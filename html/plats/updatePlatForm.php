<?php
/*
	$_GET variables:	platID
*/
	verifyUser("Administrator");

	include(GLOBAL_INCLUDES."/xhtmlHeader.inc");
	include(APPLICATION_HOME."/includes/banner.inc");
	include(APPLICATION_HOME."/includes/menubar.inc");
	include(APPLICATION_HOME."/includes/sidebar.inc");

	require_once(APPLICATION_HOME."/classes/Plat.inc");
	$plat = new Plat($_GET['platID']);
?>
<div id="mainContent">
	<?php include(GLOBAL_INCLUDES."/errorMessages.inc"); ?>

	<form method="post" action="updatePlat.php">
	<fieldset><legend>Plat</legend>
		<input name="platID" type="hidden" value="<?php echo $_GET['platID']; ?>" />

		<table>
		<tr><td><label for="name">Name</label></td>
			<td><input name="name" id="name" value="<?php echo $plat->getName(); ?>" /></td></tr>
		<tr><td><label for="townshipID">Township</label></td>
			<td><select name="townshipID" id="townshipID">
				<?php
					$townships = new TownshipList();
					$townships->find();
					foreach($townships as $township)
					{
						if ($plat->getTownshipID() != $township->getTownshipID()) { echo "<option value=\"{$township->getTownshipID()}\">{$township->getName()}</option>"; }
						else { echo "<option value=\"{$township->getTownshipID()}\" selected=\"selected\">{$township->getName()}</option>"; }
					}
				?>
				</select>
			</td>
		</tr>
		<tr><td><label for="type">Plat Type</label></td>
			<td><select name="type" id="type">
				<?php
					$platTypes = new PlatTypeList();
					$platTypes->find();
					foreach($platTypes as $platType)
					{
						if ($plat->getType() != $platType->getType()) { echo "<option value=\"{$platType->getType()}\">{$platType->getDescription()}</option>"; }
						else { echo "<option value=\"{$platType->getType()}\" selected=\"selected\">{$platType->getDescription()}</option>"; }
					}
				?>
				</select>
			</td>
		</tr>
		<tr><td><label for="cabinet">Cabinet</label></td>
			<td><input name="cabinet" id="cabinet" value="<?php echo $plat->getCabinet(); ?>" /></td></tr>
		<tr><td><label for="envelope">Envelope</label></td>
			<td><input name="envelope" id="envelope" value="<?php echo $plat->getEnvelope(); ?>" /></td></tr>
		<tr><td colspan="2">
				<div><label for="notes">Notes</label></div>
				<textarea name="notes" id="notes" rows="3" cols="60"><?php echo $plat->getNotes(); ?></textarea>
		</td></tr>
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