<h2>Add new Segment</h2>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<fieldset><legend>Segment Infom</legend>
	<table>
	<tr><td><label for="segment-tag" class="required">Tag</label></td>
		<td><input name="segment[tag]" id="segment-tag" value="<?php if(isset($_POST['segment']['tag'])) echo $_POST['segment']['tag']; ?>" /></td></tr>
	<tr><td><label for="segment-startingNumber" class="required">Start Number</label></td>
		<td><input name="segment[startingNumber]" id="segment-startingNumber" value="<?php if (isset($_POST['segment']['startingNumber'])) echo $_POST['segment']['startingNumber']; ?>" /></td></tr>
	<tr><td><label for="segment-endingNumber" class="required">End Number</label></td>
		<td><input name="segment[endingNumber]" id="segment-endingNumber" value="<?php if (isset($_POST['segment']['endingNumber'])) echo $_POST['segment']['endingNumber']; ?>" /></td></tr>
	<tr><td><label for="segment-jurisdiction_id" class="required">Jurisdiction</label></td>
		<td><select name="segment[jurisdiction_id]" id="segment-jurisdiction_id">
			<?php
				$list = new JurisdictionList();
				$list->find();
				foreach($list as $jurisdiction)
				{
					if (isset($_POST['segment']['jurisdiction_id']) && $_POST['segment']['jurisdiction_id']==$jurisdiction->getId())
					{
						echo "<option value=\"{$jurisdiction->getId()}\" selected=\"selected\">{$jurisdiction->getName()}</option>";
					}
					else { echo "<option value=\"{$jurisdiction->getId()}\">{$jurisdiction->getName()}</option>"; }
				}
			?>
			</select>
		</td></tr>
	<tr><td><label for="segment-status_id" class="required">Status</label></td>
		<td><select name="segment[status_id]" id="segment-status_id">
			<?php
				$list = new StatusList();
				$list->find();
				foreach($list as $status)
				{
					if (isset($_POST['segment']['status_id']) && $_POST['segment']['status_id']==$status->getId())
					{
						echo "<option value=\"{$status->getId()}\" selected=\"selected\">{$status->getStatus()}</option>";
					}
					else { echo "<option value=\"{$status->getId()}\">{$status->getStatus()}</option>"; }
				}
			?>
			</select>
		</td></tr>
	<tr><td><label for="segment-maintenanceResponsibility" class="required">Maintenance Responsibility</label></td>
		<td><select name="segment[maintenanceResponsibility]" id="segment-maintenanceResponsibility">
			<?php
				$list = new MaintenanceResponsibilityList();
				$list->find();
				foreach($list as $maintain)
				{
					if (isset($_POST['segment']['maintenanceResponsibility']) && $_POST['segment']['maintenanceResponsibility']==$maintain)
					{
						echo "<option selected=\"selected\">$maintain</option>";
					}
					else { echo "<option>$maintain</option>"; }
				}
			?>
		</td></tr>
	<tr><td><label for="segment-complete" class="required">Complete</label></td>
		<td><select name="segment[complete]" id="segment-complete">
			<?php
				$list = new CompleteList();
				$list->find();
				foreach($list as $complete)
				{
					if (isset($_POST['segment']['complete']) && $_POST['segment']['complete']==$complete)
					{
						echo "<option selected=\"selected\">$complete</option>";
					}
					else { echo "<option>$complete</option>"; }
				}
			?>
			</select>
		</td></tr>
	<tr><td><label for="segment-speedLimit">Speed Limit</label></td>
		<td><input name="segment[speedLimit]" id="segment-speedLimit" value="<?php if(isset($_POST['segment']['speedLimit'])) echo $_POST['segment']['speedLimit']; ?>" /></td></tr>
	<tr><td><label for="segment-INDOTSegmentID">INDOT Segment ID</label></td>
		<td><input name="segment[INDOTSegmentID]" id="segment-INDOTSegmentID" value="<?php if(isset($_POST['segment']['INDOTSegmentID'])) echo $_POST['segment']['INDOTSegmentID']; ?>" /></td></tr>
	<tr><td><label for="segment-transportationClass">Transportation Class</label></td>
		<td><select name="segment[transportationClass]" id="segment-transportationClass">
			<?php
				$list = new TransportationClassList();
				$list->find();
				foreach($list as $class)
				{
					if (isset($_POST['segment']['transportationClass']) && $_POST['segment']['transportationClass']==$class)
					{
						echo "<option selected=\"selected\">$class</option>";
					}
					else { echo "<option>$class</option>"; }
				}
			?>
			</select>
		</td></tr>
	<tr><td><label for="segment-leftLowNumber">Left Low Number</label></td>
		<td><input name="segment[leftLowNumber]" id="segment-leftLowNumber" value="<?php if(isset($_POST['segment']['leftLowNumber'])) echo $_POST['segment']['leftLowNumber']; ?>" /></td></tr>
	<tr><td><label for="segment-leftHighNumber">Left High Number</label></td>
		<td><input name="segment[leftHighNumber]" id="segment-leftHighNumber" value="<?php if(isset($_POST['segment']['leftHighNumber'])) echo $_POST['segment']['leftHighNumber']; ?>" /></td></tr>
	<tr><td><label for="segment-rightLowNumber">Right Low Number</label></td>
		<td><input name="segment[rightLowNumber]" id="segment-rightLowNumber" value="<?php if(isset($_POST['segment']['rightLowNunber'])) echo $_POST['segment']['rightLowNumber']; ?>" /></td></tr>
	<tr><td><label for="segment-rightHighNumber">Right High Number</label></td>
		<td><input name="segment[rightHighNumber]" id="segment-rightHighNumber" value="<?php if(isset($_POST['segment']['rightHighNumber'])) echo $_POST['segment']['rightHighNumber']; ?>" /></td></tr>
	<tr><td><label for="segment-rcinode1">RCI Node 1</label></td>
		<td><input name="segment[rcinode1]" id="segment-rcinode1" value="<?php if(isset($_POST['segment']['rcinode1'])) echo $_POST['segment']['rcinode1']; ?>" /></td></tr>
	<tr><td><label for="segment-rcinode2">RCI Node 2</label></td>
		<td><input name="segment[rcinode2]" id="segment-rcinode2" value="<?php if(isset($_POST['segment']['rcinode2'])) echo $_POST['segment']['rcinode2']; ?>" /></td></tr>
	<tr><td><label for="segment-lownode">Low Node</label></td>
		<td><input name="segment[lownode]" id="segment-lownode" value="<?php if(isset($_POST['segment']['lownode'])) echo $_POST['segment']['lownode']; ?>" /></td></tr>
	<tr><td><label for="segment-highnode">High Node</label></td>
		<td><input name="segment[highnode]" id="segment-highnode" value="<?php if(isset($_POST['segment']['highnode'])) echo $_POST['segment']['highnode']; ?>" /></td></tr>
	<tr><td><label for="segment-lowXCoordinate">Low X Coordinate</label></td>
		<td><input name="segment[lowXCoordinate]" id="segment-lowXCoordinate" value="<?php if(isset($_POST['segment']['lowXCoordinate'])) echo $_POST['segment']['lowXCoordinate']; ?>" /></td></tr>
	<tr><td><label for="segment-lowYCoordinate">Low Y Coordinate</label></td>
		<td><input name="segment[lowYCoordinate]" id="segment-lowYCoordinate" value="<?php if(isset($_POST['segment']['lowYCoordinate'])) echo $_POST['segment']['lowYCoordinate']; ?>" /></td></tr>
	<tr><td><label for="segment-highXCoordinate">High X Coordinate</label></td>
		<td><input name="segment[highXCoordinate]" id="segment-highXCoordinate" value="<?php if(isset($_POST['segment']['highXCoordinate'])) echo $_POST['segment']['highXCoordinate']; ?>" /></td></tr>
	<tr><td><label for="segment-highYCoordinate">High Y Coordinate</label></td>
		<td><input name="segment[highYCoordinate]" id="segment-highYCoordinate" value="<?php if(isset($_POST['segment']['highYCoordinate'])) echo $_POST['segment']['highYCoordinate']; ?>" /></td></tr>
	<tr><td><label for="segment-travel">Travel</label></td>
		<td><select name="segment[travel]" id="segment-travel">
			<?php
				$list = new TravelList();
				$list->find();
				foreach($list as $travel)
				{
					if (isset($_POST['segment']['travel']) && $_POST['segment']['travel']==$travel)
					{
						echo "<option selected=\"selected\">$travel</option>";
					}
					else { echo "<option>$travel</option>"; }
				}
			?>
			</select>
		</td></tr>
	<tr><td><label for="segment-travelDirection_id">Travel Direction</label></td>
		<td><select name="segment[travelDirection_id]" id="segment-travelDirection_id"><option></option>
			<?php
				$list = new DirectionList();
				$list->find();
				foreach($list as $direction)
				{
					if (isset($_POST['segment']['travelDirection_id']) && $_POST['segment']['travelDirection_id']==$direction->getId())
					{
						echo "<option value=\"{$direction->getId()}\" selected=\"selected\">{$direction->getCode()}</option>";
					}
					else { echo "<option value=\"{$direction->getId()}\">{$direction->getCode()}</option>"; }
				}
			?>
			</select>
		</td></tr>
	<tr><td><label for="segment-rclback">RCL Back</label></td>
		<td><input name="segment[rclback]" id="segment-rclback" value="<?php if(isset($_POST['segment']['rclback'])) echo $_POST['segment']['rclback']; ?>" /></td></tr>
	<tr><td><label for="segment-rclnext">RCL Next</label></td>
		<td><input name="segment[rclnext]" id="segment-rclnext" value="<?php if(isset($_POST['segment']['rclnext'])) echo $_POST['segment']['rclnext']; ?>" /></td></tr>
	<tr><td><label for="segment-classRow">Class Row</label></td>
		<td><input name="segment[classRow]" id="segment-classRow" value="<?php if(isset($_POST['segment']['classRow'])) echo $_POST['segment']['classRow']; ?>" /></td></tr>
	<tr><td><label for="segment-mapArea">Map Area</label></td>
		<td><input name="segment[mapArea]" id="segment-mapArea" value="<?php if(isset($_POST['segment']['mapArea'])) echo $_POST['segment']['mapArea']; ?>" /></td></tr>
	<tr><td colspan="2">
			<div><label for="segment-notes">Notes</label></div>
			<div><textarea name="segment[notes]" id="segment-notes" rows="3" cols="60"><?php if(isset($_POST['segment']['notes'])) echo $_POST['segment']['notes']; ?></textarea></div>
		</td></tr>
	</table>

	<button type="submit" class="submit">Submit</button>
</fieldset>
</form>