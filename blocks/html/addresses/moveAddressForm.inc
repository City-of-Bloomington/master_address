<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
$location = $this->location;
$old_status = $location->getStatus()->getId();
$address = $this->address;

?>
<h1>Find a Location</h1>
<form method="get" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>">
	<fieldset><legend>Move Address to Different Location</legend>
		<input type="hidden" name="lid" value="<?php echo $location->getId(); ?>" />
		<input type="hidden" name="street_address_id" value="<?php echo $address->getId(); ?>" />	
		<div>
			<label>From</label>
			<table>
				<tr><th>Status</th>
					<th>Location ID</th>
					<th>Address</th>
				</tr>
				<tr><td><?php echo $location->getStatus(); ?></td>
					<td><?php echo $location->getLid(); ?></td>
					<td><?php echo $address->getStreetAddress();  ?></td>
				</tr>
			</table>
		</div>
		<div>
			<label>What to do with previous location?</label>
			<select name="new_status" id="old_location_status">
				<?php
				$statuses = new AddressStatusList();
				$statuses->find();
				foreach($statuses as $status){
				   $selected = $old_status == $status->getId()?"selected=\"selected\"":"";
				   echo "<option $selected value=\"{$status->getId()}\">$status</option>";
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
			<label>Or</label>
		</div>
		
		<div>			
			<input name="new_address" id="new_address"
					value="<?php echo isset($_GET['new_address']) ? View::escape($_GET['new_address']) : 'Search Locations'; ?>" />
		</div>
		<button type="submit" class="search">Search</button>
		</form>
		
		<form method="post" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>">
		<input type="hidden" name="lid" value="<?php echo $location->getId(); ?>" />
		<input type="hidden" name="street_address_id" value="<?php echo $address->getId(); ?>" />
		<input type="hidden" name="new_status" id="new_status2" value="3" />
		<?php
		if(isset($this->locationList)){
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
				    <td>{$loc->getId()}</td>
					<td>{$loc->getStatus()}</td>
					<td>{$loc->getStatus()->getEffective_start_date()}</td>
					<td>{$address->getStreetAddress()}</td>
					<td>{$address->getStreet()->getTown()->getDescription()}</td>
					</tr>";
			}
			echo "</table>
				";
		}
		?>
		<button type=\"submit\" class=\"submit\">Move Location</button>

	</fieldset>
</form>
