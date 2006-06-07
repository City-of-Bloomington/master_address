<?php
/*
	$_GET variables:	id
*/
	verifyUser("Administrator");

	include(GLOBAL_INCLUDES."/xhtmlHeader.inc");
	include(APPLICATION_HOME."/includes/banner.inc");
	include(APPLICATION_HOME."/includes/menubar.inc");
	include(APPLICATION_HOME."/includes/sidebar.inc");

	$plat = new Plat($_GET['id']);
?>
<div id="mainContent">
	<?php include(GLOBAL_INCLUDES."/errorMessages.inc"); ?>

	<form method="post" action="updatePlat.php">
	<fieldset><legend>Plat</legend>
		<input name="id" type="hidden" value="<?php echo $_GET['id']; ?>" />

		<table>
		<tr><td><label for="name">Name</label></td>
			<td><input name="name" id="name" value="<?php echo $plat->getName(); ?>" /></td></tr>
		<tr><td><label for="township_id">Township</label></td>
			<td><select name="township_id" id="township_id">
				<?php
					$townships = new TownshipList();
					$townships->find();
					foreach($townships as $township)
					{
						if ($plat->getTownship_id() != $township->getId()) { echo "<option value=\"{$township->getId()}\">{$township->getName()}</option>"; }
						else { echo "<option value=\"{$township->getId()}\" selected=\"selected\">{$township->getName()}</option>"; }
					}
				?>
				</select>
			</td>
		</tr>
		<tr><td><label for="platType_id">Plat Type</label></td>
			<td><select name="platType_id" id="platType_id">
				<?php
					$platTypes = new PlatTypeList();
					$platTypes->find();
					foreach($platTypes as $platType)
					{
						if ($plat->getPlatType_id() != $platType->getId()) { echo "<option value=\"{$platType->getId()}\">{$platType->getType()}</option>"; }
						else { echo "<option value=\"{$platType->getId()}\" selected=\"selected\">{$platType->getType()}</option>"; }
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