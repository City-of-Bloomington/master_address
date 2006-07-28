<h1>Find a Building</h1>
<form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<fieldset><legend>Building Info</legend>
	<table>
	<tr><td><label for="building-id">ID</label></td>
		<td><input name="building[id]" id="building-id" value="<?php if(isset($_GET['building']['id'])) echo $_GET['building']['id']; ?>" /></td></tr>
	<tr><td><label for="building-GISTag">Tag</label></td>
		<td><input name="building[GISTag]" id="building-GISTag" value="<?php if(isset($_GET['building']['GISTag'])) echo $_GET['building']['GISTag']; ?>" /></td></tr>
	<tr><td><label for="building-name">Name</label></td>
		<td><input name="building[name]" id="building-name" value="<?php if(isset($_GET['building']['name'])) echo $_GET['building']['name']; ?>" /></td></tr>
	<tr><td><label for="building-startDate-mon">Start Date</label></td>
		<td><select name="building[startDate][mon]" id="building-startDate-mon"><option></option>
			<?php
				$startDate = isset($_GET['building']['startDate']) ? $_POST['building']['startDate'] : array('year'=>'','mon'=>'','mday'=>'');
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
				$endDate = isset($_GET['building']['endDate']) ? $_POST['building']['endDate'] : array('year'=>'','mon'=>'','mday'=>'');
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
		<td><select name="building[status_id]" id="building-status_id"><option></option>
			<?php
				$list = new StatusList();
				$list->find();
				foreach($list as $status)
				{
					if (isset($_GET['building']['status_id']) && $_GET['building']['status_id']==$status->getId())
						{ echo "<option value=\"{$status->getId()}\" selected=\"selected\">{$status->getStatus()}</option>"; }
					else { echo "<option value=\"{$status->getId()}\">{$status->getStatus()}</option>"; }
				}
			?>
			</select>
		</td>
	</tr>
	</table>

	<button type="submit" class="search">Search</button>
</fieldset>
</form>