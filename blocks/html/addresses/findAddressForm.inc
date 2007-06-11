<h2>Advanced Search</h2>
<form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<fieldset><legend>Address Info</legend>
	<table>
	<tr><th><label for="address-number">Number</label></th>
		<th><label for="address-suffix">Suffix</label></th>
		<th><label for="name-name">Street Name</label></th>
	</tr>
	<tr><td><input name="address[number]" id="address-number" size="5" value="<?php if(isset($_GET['address']['number'])) echo $_GET['address']['number']; ?>" /></td>
		<td><input name="address[suffix]" id="address-suffix" size="3" value="<?php if(isset($_GET['address']['suffix'])) echo $_GET['address']['suffix']; ?>" /></td>
		<td><select name="name[direction_id]" id="name-direction_id"><option></option>
			<?php
				$directionList = new DirectionList();
				$directionList->find();
				foreach($directionList as $direction)
				{
					if (isset($_GET['name']['direction_id']) && $_GET['name']['direction_id']==$direction->getId())
						{ echo "<option value=\"{$direction->getId()}\" selected=\"selected\">{$direction->getCode()}</option>"; }
					else { echo "<option value=\"{$direction->getId()}\">{$direction->getCode()}</option>"; }
				}
			?>
			</select>
			<input name="name[name]" id="name-name" value="<?php if(isset($_GET['name']['name'])) echo $_GET['name']['name']; ?>" />
			<select name="name[suffix_id]" id="name-suffix_id"><option></option>
			<?php
				$list = new SuffixList();
				$list->find();
				foreach($list as $suffix)
				{
					if (isset($_GET['name']['suffix']) && $_GET['name']['suffix']==$suffix->getSuffix())
						{ echo "<option value=\"{$suffix->getId()}\" selected=\"selected\">{$suffix->getSuffix()}</option>"; }
					else { echo "<option value=\"{$suffix->getId()}\">{$suffix->getSuffix()}</option>"; }
				}
			?>
			</select>
			<select name="name[postDirection_id]" id="name-postDirection_id"><option></option>
			<?php
				foreach($directionList as $direction) { echo "<option value=\"{$direction->getId()}\">{$direction->getCode()}</option>"; }
			?>
			</select>
	</tr>
	</table>

	<table>
	<tr><td><label for="address-addressType">Type</label></td>
		<td colspan="2">
			<select name="address[addressType]" id="address-addressType"><option></option>
			<?php
				$list = new AddressTypeList();
				$list->find();
				foreach($list as $type)
				{
					if (isset($_GET['address']['addressType']) && $_GET['address']['addressType']==$type)
						{ echo "<option selected=\"selected\">$type</option>"; }
					else { echo "<option>$type</option>"; }
				}
			?>
			</select>
		</td></tr>
	<tr><td><label for="address-city_id">City</label></td>
		<td colspan="2">
			<select name="address[city_id]" id="address-city_id"><option></option>
			<?php
				$list = new CityList();
				$list->find();
				foreach($list as $city)
				{
					if (isset($_GET['address']['city_id']) && $_GET['address']['city_id']==$city->getId())
						{ echo "<option value=\"{$city->getId()}\" selected=\"selected\">{$city->getName()}</option>"; }
					else { echo "<option value=\"{$city->getId()}\">{$city->getName()}</option>"; }
				}
			?>
			</select>
		</td></tr>
	<tr><td><label for="address-zip">Zip</label></td>
		<td colspan="2">
			<input name="address[zip]" id="address-zip" size="5" maxlength="5" value="<?php if(isset($_GET['address']['zip'])) echo $_GET['address']['zip']; ?>" />
			<input name="address[zipplus4]" id="address-zipplus4" size="4" maxlength="4" value="<?php if(isset($_GET['address']['zipplus4'])) echo $_GET['address']['zipplus4']; ?>" />
		</td></tr>
	<tr><td><label for="address-addressStatus_id">Status</label></td>
		<td colspan="2">
			<select name="address[addressStatus_id]" id="address-addressStatus_id"><option></option>
			<?php
				$list = new StatusList("address");
				$list->find();
				foreach($list as $status)
				{
					if (isset($_GET['address']['addressStatus_id']) && $_GET['address']['addressStatus_id']==$status->getId())
						{ echo "<option selected=\"selected\">{$status->getStatus()}</option>"; }
					else { echo "<option>{$status->getStatus()}</option>"; }
				}
			?>
			</select>
		</td></tr>
	<tr><td><label for="address-active_yes">Active</label></td>
		<td colspan="2">
			<label><input type="radio" name="address[active]" id="address-active_yes" value="Y" <?php if(isset($_GET['address']['active']) && $_GET['address']['active']=="Y") echo "checked=\"checked\""; ?> />Yes</label>
			<label><input type="radio" name="address[active]" id="address-active_no" value="N" <?php if(isset($_GET['address']['active']) && $_GET['address']['active']=="N") echo "checked=\"checked\""; ?> />No</label>
		</td>
	</tr>
	<tr><td><label for="address-startDate-mon">Start Date</label></td>
		<td colspan="2">
			<select name="address[startDate][mon]" id="address-startDate-mon"><option></option>
			<?php
				$startDate = isset($_GET['address']['startDate']) ? $_GET['address']['startDate'] : array('year'=>'','mon'=>'','mday'=>'');
				for($i=1; $i<=12; $i++)
				{
					if ($startDate['mon'] != $i) { echo "<option>$i</option>"; }
					else { echo "<option selected=\"selected\">$i</option>"; }
				}
			?>
			</select>
			<select name="address[startDate][mday]"><option></option>
			<?php
				for($i=1; $i<=31; $i++)
				{
					if ($startDate['mday'] != $i) { echo "<option>$i</option>"; }
					else { echo "<option selected=\"selected\">$i</option>"; }
				}
			?>
			</select>
			<input name="address[startDate][year]" id="address-startDate-year" size="4" maxlength="4" value="<?php echo $startDate['year']; ?>" />
		</td>
	</tr>
	<tr><td><label for="address-endDate-mon">End Date</label></td>
		<td colspan="2">
			<select name="address[endDate][mon]" id="address-endDate-mon"><option></option>
			<?php
				$endDate = isset($_GET['address']['endDate']) ? $_GET['address']['endDate'] : array('year'=>'','mon'=>'','mday'=>'');
				for($i=1; $i<=12; $i++)
				{
					if ($endDate['mon'] != $i) { echo "<option>$i</option>"; }
					else { echo "<option selected=\"selected\">$i</option>"; }
				}
			?>
			</select>
			<select name="address[endDate][mday]"><option></option>
			<?php
				for($i=1; $i<=31; $i++)
				{
					if ($endDate['mday'] != $i) { echo "<option>$i</option>"; }
					else { echo "<option selected=\"selected\">$i</option>"; }
				}
			?>
			</select>
			<input name="address[endDate][year]" id="address-endDate-year" size="4" maxlength="4" value="<?php echo $endDate['year']; ?>" />
		</td>
	</tr>
	<tr><td colspan="2">
			<div><label for="address-notes">Notes</label></div>
			<textarea name="address[notes]" id="address-notes" rows="3" cols="60"><?php if(isset($_GET['address']['notes'])) echo $_GET['address']['notes']; ?></textarea>
		</td>
	</tr>
	</table>

	<button type="submit" class="search">Search</button>
</fieldset>
</form>