<h2>Add new Segment</h2>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<fieldset><legend>Segment Info</legend>
	<table>
	<tr><td><label for="segment-tag" class="required">Tag</label></td>
		<td><input name="segment[tag]" id="segment-tag" value="<?php if(isset($_POST['segment']['tag'])) echo $_POST['segment']['tag']; ?>" /></td></tr>
	<tr><td><label for="segment-lowAddressNumber" class="required">Low Number</label></td>
		<td><input name="segment[lowAddressNumber]" id="segment-lowAddressNumber" value="<?php if (isset($_POST['segment']['lowAddressNumber'])) echo $_POST['segment']['lowAddressNumber']; ?>" /></td></tr>
	<tr><td><label for="segment-highAddressNumber" class="required">High Number</label></td>
		<td><input name="segment[highAddressNumber]" id="segment-highAddressNumber" value="<?php if (isset($_POST['segment']['highAddressNumber'])) echo $_POST['segment']['highAddressNumber']; ?>" /></td></tr>
	<tr><td><label for="segment-leftLowAddressNumber" class="required">Left Low Number</label></td>
		<td><input name="segment[leftLowAddressNumber]" id="segment-leftLowAddressNumber" value="<?php if(isset($_POST['segment']['leftLowAddressNumber'])) echo $_POST['segment']['leftLowAddressNumber']; ?>" /></td></tr>
	<tr><td><label for="segment-leftHighAddressNumber" class="required">Left High Number</label></td>
		<td><input name="segment[leftHighAddressNumber]" id="segment-leftHighAddressNumber" value="<?php if(isset($_POST['segment']['leftHighAddressNumber'])) echo $_POST['segment']['leftHighAddressNumber']; ?>" /></td></tr>
	<tr><td><label for="segment-rightLowAddressNumber" class="required">Right Low Number</label></td>
		<td><input name="segment[rightLowAddressNumber]" id="segment-rightLowAddressNumber" value="<?php if(isset($_POST['segment']['rightLowAddressNumber'])) echo $_POST['segment']['rightLowAddressNumber']; ?>" /></td></tr>
	<tr><td><label for="segment-rightHighAddressNumber" class="required">Right High Number</label></td>
		<td><input name="segment[rightHighAddressNumber]" id="segment-rightHighAddressNumber" value="<?php if(isset($_POST['segment']['rightHighAddressNumber'])) echo $_POST['segment']['rightHighAddressNumber']; ?>" /></td></tr>
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
				$statusList = new StatusList();
				$statusList->find();
				foreach($statusList as $status)
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
	<tr><td><label for="segment-constructionStatus_id" class="required">Construction Status</label></td>
		<td><select name="segment[constructionStatus_id]" id="segment-constructionStatus_id">
			<?php
				foreach($statusList as $status)
				{
					if (isset($_POST['segment']['constructionStatus_id']) && $_POST['segment']['constructionStatus_id']==$status->getId())
					{
						echo "<option value=\"{$status->getId()}\" selected=\"selected\">{$status->getStatus()}</option>";
					}
					else { echo "<option value=\"{$status->getId()}\">{$status->getStatus()}</option>"; }
				}
			?>
			</select>
		</td></tr>
	<tr><td><label for="segment-maintainedBy" class="required">Maintained By</label></td>
		<td><select name="segment[maintainedBy]" id="segment-maintainedBy">
			<?php
				$list = new MaintainedByList();
				$list->find();
				foreach($list as $maintain)
				{
					if (isset($_POST['segment']['maintainedBy']) && $_POST['segment']['maintainedBy']==$maintain)
					{
						echo "<option selected=\"selected\">$maintain</option>";
					}
					else { echo "<option>$maintain</option>"; }
				}
			?>
		</td></tr>
	<tr><td><label for="segment-speedLimit">Speed Limit</label></td>
		<td><input name="segment[speedLimit]" id="segment-speedLimit" value="<?php if(isset($_POST['segment']['speedLimit'])) echo $_POST['segment']['speedLimit']; ?>" /></td></tr>
	<tr><td><label for="segment-thoroughfareClass_id">Thoroughfare Class</label></td>
		<td><select name="segment[thoroughfareClass_id]" id="segment-thoroughfareClass_id"><option></option>
			<?php
				$list = new ThoroughfareClassList();
				$list->find();
				foreach($list as $thoroughfare)
				{
					if (isset($_POST['segment']['thoroughfareClass']) && $_POST['segment']['thoroughfareClass']==$thoroughfare->getClass())
					{
						echo "<option value=\"{$thoroughfare->getId()}\" selected=\"selected\">{$thoroughfare->getClass()}</option>";
					}
					else { echo "<option value=\"{$thoroughfare->getId()}\">{$thoroughfare->getClass()}</option>"; }
				}
			?>
			</select>
		</td></tr>
	<tr><td><label for="segment-intersectionBack_id">Intersection Back</label></td>
		<td><input name="segment[intersectionBack_id]" id="segment-intersectionBack_id" value="<?php if(isset($_POST['segment']['intersectionBack_id'])) echo $_POST['segment']['intersectionBack_id']; ?>" /></td></tr>
	<tr><td><label for="segment-intersectionAhead_id">Intersection Ahead</label></td>
		<td><input name="segment[intersectionAhead_id]" id="segment-intersectionAhead_id" value="<?php if(isset($_POST['segment']['intersectionAhead_id'])) echo $_POST['segment']['intersectionAhead_id']; ?>" /></td></tr>
	<tr><td><label for="segment-lowAddressIntersection_id">Low Node</label></td>
		<td><input name="segment[lowAddressIntersection_id]" id="segment-lowAddressIntersection_id" value="<?php if(isset($_POST['segment']['lowAddressIntersection_id'])) echo $_POST['segment']['lowAddressIntersection_id']; ?>" /></td></tr>
	<tr><td><label for="segment-highAddressIntersection_id">High Node</label></td>
		<td><input name="segment[highAddressIntersection_id]" id="segment-highAddressIntersection_id" value="<?php if(isset($_POST['segment']['highAddressIntersection_id'])) echo $_POST['segment']['highAddressIntersection_id']; ?>" /></td></tr>
	<tr><td><label for="segment-labelX">Label X</label></td>
		<td><input name="segment[labelX]" id="segment-labelX" value="<?php if(isset($_POST['segment']['labelX'])) echo $_POST['segment']['labelX']; ?>" /></td></tr>
	<tr><td><label for="segment-labelY">Label Y</label></td>
		<td><input name="segment[labelY]" id="segment-labelY" value="<?php if(isset($_POST['segment']['labelY'])) echo $_POST['segment']['labelY']; ?>" /></td></tr>
	<tr><td><label for="segment-travel">Travel</label></td>
		<td><select name="segment[travelWayCode]" id="segment-travelWayCode"><option></option>
			<?php
				$list = new TravelWayCodeList();
				$list->find();
				foreach($list as $travelCode)
				{
					if (isset($_POST['segment']['travelWayCode']) && $_POST['segment']['travelWayCode']==$travelCode)
					{
						echo "<option selected=\"selected\">$travelCode</option>";
					}
					else { echo "<option>$travelCode</option>"; }
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
	<tr><td><label for="segment-segmentBack_id">Segment Back</label></td>
		<td><input name="segment[segmentBack_id]" id="segment-segmentBack_id" value="<?php if(isset($_POST['segment']['segmentBack_id'])) echo $_POST['segment']['segmentBack_id']; ?>" /></td></tr>
	<tr><td><label for="segment-segmentAhead_id">Segment Next</label></td>
		<td><input name="segment[segmentAhead_id]" id="segment-segmentAhead_id" value="<?php if(isset($_POST['segment']['segmentAhead_id'])) echo $_POST['segment']['segmentAhead_id']; ?>" /></td></tr>
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