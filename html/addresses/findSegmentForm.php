<?php
/*
	The start of the add address process.  They have to have a valid segment before they
	can add an address to the system.
*/
	verifyUser(array("Administrator","ADDRESS COORDINATOR"));
	include(GLOBAL_INCLUDES."/xhtmlHeader.inc");
	include(APPLICATION_HOME."/includes/banner.inc");
	include(APPLICATION_HOME."/includes/menubar.inc");
	include(APPLICATION_HOME."/includes/sidebar.inc");
?>
<div id="mainContent">
	<?php include(GLOBAL_INCLUDES."/errorMessages.inc"); ?>
	<h1>Add an address</h1>
	<form method="get" action="chooseSegment.php">
	<fieldset><legend>Find a segment</legend>
		<table>
		<tr><th><label for="number" class="required">Number</label></th>
			<th><label for="suffix">Suffix</label></th>
			<th><label for="name" class="required">Street Name</label></th>
		</tr>
		<tr><td><input name="number" id="number" size="5" /></td>
			<td><input name="suffix" id="suffix" size="3" /></td>
			<td><select name="direction_id" id="direction_id"><option></option>
				<?php
					$directionList = new DirectionList();
					$directionList->find();
					foreach($directionList as $direction) { echo "<option value=\"{$direction->getId()}\">{$direction->getCode()}</option>"; }
				?>
				</select>
				<input name="name" id="name" />
				<select name="suffix_id" id="suffix_id"><option></option>
				<?php
					$list = new SuffixList();
					$list->find();
					foreach($list as $suffix) { echo "<option value=\"{$suffix->getId()}\">{$suffix->getSuffix()}</option>"; }
				?>
				</select>
				<select name="postDirection_id" id="postDirection_id"><option></option>
				<?php
					foreach($directionList as $direction) { echo "<option value=\"{$direction->getId()}\">{$direction->getCode()}</option>"; }
				?>
				</select>
		</tr>
		</table>

		<button type="submit" class="submit">Submit</button>
	</fieldset>
	</form>
</div>
<?php
	include(APPLICATION_HOME."/includes/footer.inc");
	include(GLOBAL_INCLUDES."/xhtmlFooter.inc");
?>