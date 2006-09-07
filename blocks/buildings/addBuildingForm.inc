<h1>Add a new Building</h1>
<p>Use this form to add multiple buildings.  Close this window when you are done.</p>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<fieldset><legend>Building Info</legend>
	<table>
	<tr><td><label for="building-GISTag">Tag</label></td>
		<td><input name="building[GISTag]" id="building-GISTag" value="<?php if(isset($_POST['building']['GISTag'])) echo $_POST['building']['GISTag']; ?>" /></td></tr>
	<tr><td><label for="building-name">Name</label></td>
		<td><input name="building[name]" id="building-name" value="<?php if(isset($_POST['building']['name'])) echo $_POST['building']['name']; ?>" /></td></tr>
	<tr><td><label for="building-startDate-mon">Start Date</label></td>
		<td><select name="building[startDate][mon]" id="building-startDate-mon"><option></option>
			<?php
				$startDate = isset($_POST['building']['startDate']) ? $_POST['building']['startDate'] : getdate();
				for($i=1; $i<=12; $i++)
				{
					if ($i != $startDate['mon']) { echo "<option>$i</option>"; }
					else { echo "<option selected=\"selected\">$i</option>"; }
				}
			?>
			</select>
			<select name="building[startDate][mday]"><option></option>
			<?php
				for($i=1; $i<=31; $i++)
				{
					if ($i != $startDate['mday']) { echo "<option>$i</option>"; }
					else { echo "<option selected=\"selected\">$i</option>"; }
				}
			?>
			</select>
			<input name="building[startDate][year]" value="<?php echo $startDate['year']; ?>" />
		</td></tr>
	<tr><td><label for="building-endDate-mon">End Date</label></td>
		<td><select name="building[endDate][mon]" id="building-endDate-mon"><option></option>
			<?php
				$endDate = isset($_POST['building']['endDate']) ? $_POST['building']['endDate'] : array('year'=>'','mon'=>'','mday'=>'');
				for($i=1; $i<=12; $i++)
				{
					if ($i != $endDate['mon']) { echo "<option>$i</option>"; }
					else { echo "<option selected=\"selected\">$i</option>"; }
				}
			?>
			</select>
			<select name="building[endDate][mday]"><option></option>
			<?php
				for($i=1; $i<=31; $i++)
				{
					if ($i != $endDate['mday']) { echo "<option>$i</option>"; }
					else { echo "<option selected=\"selected\">$i</option>"; }
				}
			?>
			</select>
			<input name="building[endDate][year]" value="<?php echo $endDate['year']; ?>" />
	</td></tr>
	<tr><td><label for="building-status_id">Status</label></td>
		<td><select name="building[status_id]" id="building-status_id">
			<?php
				$list = new StatusList();
				$list->find();
				foreach($list as $status)
				{
					if (isset($_POST['building']['status_id']) && $_POST['building']['status_id']==$status->getId())
						{ echo "<option value=\"{$status->getId()}\" selected=\"selected\">{$status->getStatus()}</option>"; }
					else { echo "<option value=\"{$status->getId()}\">{$status->getStatus()}</option>"; }
				}
			?>
			</select>
		</td>
	</tr>
	</table>

	<button type="submit" class="search">Search</button>
	<button type="button" class="done" onclick="self.close();">Done</button>
</fieldset>
</form>