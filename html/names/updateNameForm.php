<?php
/*
	$_GET variables:	nameID
*/
	verifyUser("Administrator");

	include(GLOBAL_INCLUDES."/xhtmlHeader.inc");
	include(APPLICATION_HOME."/includes/banner.inc");
	include(APPLICATION_HOME."/includes/menubar.inc");
	include(APPLICATION_HOME."/includes/sidebar.inc");

	require_once(APPLICATION_HOME."/classes/Name.inc");
	$name = new Name($_GET['nameID']);
?>
<div id="mainContent">
	<?php include(GLOBAL_INCLUDES."/errorMessages.inc"); ?>

	<form method="post" action="updateName.php">
	<fieldset><legend>Name</legend>
		<input name="nameID" type="hidden" value="<?php echo $name->getNameID(); ?>" />

		<table>
		<tr><td><label for="directionCode">Dir</label></td>
			<td><label for="name">Name</label></td>
			<td><label for="suffixAbbreviation">Suff</label></td>
			<td><label for="postDirectionCode">Dir</label></td>
		</tr>
		<tr><td><select name="directionCode" id="directionCode"><option></option>
				<?php
					$directionList = new DirectionList();
					$directionList->find();
					foreach($directionList as $direction)
					{
						if ($name->getDirectionCode() != $direction->getDirectionCode()) { echo "<option>{$direction->getDirectionCode()}</option>"; }
						else { echo "<option selected=\"selected\">{$direction->getDirectionCode()}</option>"; }
					}
				?>
				</select>
			</td>
			<td><input name="name" id="name" value="<?php echo $name->getName(); ?>" /></td>
			<td><select name="suffixAbbreviation" id="suffixAbbreviation"><option></option>
				<?php
					$suffixList = new SuffixList();
					$suffixList->find();
					foreach($suffixList as $suffix)
					{
						if ($name->getSuffixAbbreviation() != $suffix->getSuffixAbbreviation()) { echo "<option>{$suffix->getSuffixAbbreviation()}</option>"; }
						else { echo "<option selected=\"selected\">{$suffix->getSuffixAbbreviation()}</option>"; }
					}
				?>
				</select>
			</td>
			<td><select name="postDirectionCode" id="postDirectionCode"><option></option>
				<?php
					# This reuses the directionList we created for the $name->directionCode
					foreach($directionList as $direction)
					{
						if ($name->getPostDirectionCode() != $direction->getDirectionCode()) { echo "<option>{$direction->getDirectionCode()}</option>"; }
						else { echo "<option selected=\"selected\">{$direction->getDirectionCode()}</option>"; }
					}
				?>
				</select>
			</td>
		</tr>
		</table>
	</fieldset>
	<fieldset><legend>Info</legend>
		<table>
		<tr><td><label for="startMonth">Start Date</label></td>
			<td><select name="startMonth" id="startMonth">
				<?php
					if ($name->getStartDate()) { list($startYear,$startMonth,$startDay) = explode("-",$name->getStartDate()); }
					else { list($startYear,$startMonth,$startDay) = array("","",""); }
					for($i=1; $i<=12; $i++)
					{
						if ($startMonth != $i) { echo "<option>$i</option>"; }
						else { echo "<option selected=\"selected\">$i</option>"; }
					}
				?>
				</select>
				<select name="startDay">
				<?php
					for($i=1; $i<=31; $i++)
					{
						if ($startDay != $i) { echo "<option>$i</option>"; }
						else { echo "<option selected=\"selected\">$i</option>"; }
					}
				?>
				</select>
				<input name="startYear" id="startYear" size="4" maxlength="4" value="<?php echo $startYear; ?>" />
			</td>
		</tr>
		<tr><td><label for="endMonth">End Date</label></td>
			<td><select name="endMonth" id="endMonth">
				<?php
					if ($name->getEndDate()) { list($endYear,$endMonth,$endDay) = explode("-",$name->getEndDate()); }
					else { list($endYear,$endMonth,$endDay) = array("","",""); }
					for($i=1; $i<=12; $i++)
					{
						if ($endMonth != $i) { echo "<option>$i</option>"; }
						else { echo "<option selected=\"selected\">$i</option>"; }
					}
				?>
				</select>
				<select name="endDay">
				<?php
					for($i=1; $i<=31; $i++)
					{
						if ($endDay != $i) { echo "<option>$i</option>"; }
						else { echo "<option selected=\"selected\">$i</option>"; }
					}
				?>
				</select>
				<input name="endYear" id="endYear" size="4" maxlength="4" value="<?php echo $endYear; ?>" />
			</td>
		</tr>
		<tr><td colspan="2">
				<div><label for="notes">Notes</label></div>
				<textarea name="notes" id="notes" rows="3" cols="60"><?php echo $name->getNotes(); ?></textarea>
		</td></tr>
		</table>

	</fieldset>
	<fieldset>
		<button type="submit" class="submit">Submit</button>
		<button type="button" class="cancel" onclick="document.location.href='home.php';">Cancel</button>
	</fieldset>
	</form>
</div>
<?php
	include(APPLICATION_HOME."/includes/footer.inc");
	include(GLOBAL_INCLUDES."/xhtmlFooter.inc");
?>