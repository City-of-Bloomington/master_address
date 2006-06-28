<?php
/*
	$_GET variables:	id
*/
	verifyUser("Administrator");

	include(GLOBAL_INCLUDES."/xhtmlHeader.inc");
	include(APPLICATION_HOME."/includes/banner.inc");
	include(APPLICATION_HOME."/includes/menubar.inc");
	include(APPLICATION_HOME."/includes/sidebar.inc");

	$name = new Name($PDO,$_GET['id']);
?>
<div id="mainContent">
	<?php include(GLOBAL_INCLUDES."/errorMessages.inc"); ?>

	<h1>Edit Name:<?php echo $_GET['id']; ?></h1>

	<form method="post" action="updateName.php">
	<fieldset><legend>Name</legend>
		<input name="id" type="hidden" value="<?php echo $name->getId(); ?>" />

		<table>
		<tr><td><label for="name">Name</label></td>
			<td><select name="direction_id" id="direction_id"><option></option>
				<?php
					$directionList = new DirectionList($PDO);
					$directionList->find();
					foreach($directionList as $direction)
					{
						if ($name->getDirection_id() != $direction->getId()) { echo "<option value=\"{$direction->getId()}\">{$direction->getCode()}</option>"; }
						else { echo "<option value=\"{$direction->getId()}\" selected=\"selected\">{$direction->getCode()}</option>"; }
					}
				?>
				</select>
				<input name="name" id="name" value="<?php echo $name->getName(); ?>" />
				<select name="suffix_id" id="suffix_id"><option></option>
				<?php
					$suffixList = new SuffixList($PDO);
					$suffixList->find();
					foreach($suffixList as $suffix)
					{
						if ($name->getSuffix_id() != $suffix->getId()) { echo "<option value=\"{$suffix->getId()}\">{$suffix->getSuffix()}</option>"; }
						else { echo "<option value=\"{$suffix->getId()}\" selected=\"selected\">{$suffix->getSuffix()}</option>"; }
					}
				?>
				</select>
				<select name="postDirection_id" id="postDirection_id"><option></option>
				<?php
					# This reuses the directionList we created for the $name->directionCode
					foreach($directionList as $direction)
					{
						if ($name->getPostDirection_id() != $direction->getId()) { echo "<option value=\"{$direction->getId()}\">{$direction->getCode()}</option>"; }
						else { echo "<option value=\"{$direction->getId()}\" selected=\"selected\">{$direction->getCode()}</option>"; }
					}
				?>
				</select>
			</td>
		</tr>
		<tr><td><label for="town_id">Town</label></td>
			<td><select name="town_id" id="town_id">
				<?php
					$townList = new TownList($PDO);
					$townList->find();
					foreach($townList as $town)
					{
						if ($name->getTown_id() != $town->getId()) { echo "<option value=\"{$town->getId()}\">{$town->getName()}</option>"; }
						else { echo "<option value=\"{$town->getId()}\" selected=\"selected\">{$town->getName()}</option>"; }
					}
				?>
				</select>
			</td>
		</tr>
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
			<td><select name="endMonth" id="endMonth"><option></option>
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
				<select name="endDay"><option></option>
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
		</table>
		<div><label for="notes">Notes</label></div>
		<textarea name="notes" id="notes" rows="3" cols="60"><?php echo $name->getNotes(); ?></textarea>

	</fieldset>
	<fieldset>
		<button type="submit" class="submit">Submit</button>
		<button type="button" class="cancel" onclick="document.location.href='viewName.php?id=<?php echo $_GET['id']; ?>';">Cancel</button>
	</fieldset>
	</form>

	<?php include(APPLICATION_HOME."/includes/names/listStreets.inc"); ?>
</div>
<?php
	include(APPLICATION_HOME."/includes/footer.inc");
	include(GLOBAL_INCLUDES."/xhtmlFooter.inc");
?>