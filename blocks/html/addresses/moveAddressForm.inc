<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
$location = $this->location;
$old_status = $location->getStatus();
$address = $location->getAddress();

?>
<h1>Find a Location</h1>
<form method="get" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>">
	<fieldset><legend>Move Address to Different Location</legend>
		<input type="hidden" name="lid" value="<?php echo $location->getId(); ?>" />
		<div>
			<label>From</label>
			<table>
				<tr><th>Status</th>
					<th>Location ID</th>
					<th>Address</th>
				</tr>
				<tr><td><?php echo $location->getStatus(); ?></td>
					<td><?php echo $location->getLid(); ?></td>
					<td><?php echo $address;  ?></td>
				</tr>
			</table>
		</div>
		<div>
			<label>What to do with previous location?</label>
			<select name=old_location_status>
				<?php
				$statuses = new LocationStatusChangeList();
				$statuses->find();
				foreach($statuses as $status){
				   $selected = $old_status == $status->getDescription()?"selected=\"selected\"":"";
				   echo "<option $selected value=\"{$status->getId()}>$status</option>";
				}
				?>
			</select>
		</div>
		<div>
			<label>To</label>
			<table>
				<tr>
					<td><input type="checkbox" name="pick_lid" /></td>
					<td><input name="new_lid" size="8" /></td>
				</tr>
			</table>
			<label>Or</lable>
		</div>
		
		<div>			
			<input name="address" id="address"
					value="<?php echo isset($_GET['address']) ? View::escape($_GET['address']) : 'Search Location'; ?>" />
		</div>
		<button type="submit" class="search">Search</search>
		<?php
		if(issest($this->locationList)){
			echo "<table><tr><th></th>
				<th>Loc ID</th>
				<th>Loc Status</th>
				<th>Start Date</th>
				<th>Address</th>
				<th>Town</th></tr>";
			$list = $this->locationList;
			foreach($list as $loc){
				$address = $loc->getAddress();
				echo "<tr><td><input type=\"radio\" name=\"new_lid2\" value=\"{$loc->getLid()}\"</td>
					<td>{$loc->getStatus()}</td>
					<td>{$loc->getStartDate()}</td>
					<td>{$address}</td>
					<td>{$address->getTown()}</td>
					</tr>";
			}
			echo "</table>";
		}
		?>
	</fieldset>
</form>
