<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Address $this->address
 * @param Location $this->location The old location (we're moving from this location)
 * @param LocationList $this->locationList (Location search results)
 */
?>
<h1>Move Address to This Location</h1>
<form method="post" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>">
	<fieldset><legend>From</legend>
		<input type="hidden" name="old_lid" value="<?php echo $this->location->getId(); ?>" />
		<input type="hidden" name="address_id" value="<?php echo $this->address->getId(); ?>" />

		<table>
			<tr><th>Status</th>
				<th>Location ID</th>
				<th>Address</th>
			</tr>
			<tr><td><?php echo $this->location->getStatus(); ?></td>
				<td><?php echo $this->location->getLocation_id(); ?></td>
				<td><?php echo $this->address->getStreetAddress();  ?></td>
			</tr>
		</table>
		<div>
			<label for="old_location_status">What status should we set on the old location?</label>
		</div>
		<div>
			<select name="old_location_status" id="old_location_status">
				<?php
					$statuses = new AddressStatusList();
					$statuses->find();
					foreach($statuses as $status){
						$selected = $this->location->getStatus()->getStatus_code() == $status->getStatus_code()
									? 'selected="selected"'
									: '';
						echo "<option $selected value=\"{$status->getId()}\">$status</option>";
					}
				?>
			</select>
		</div>
	</fieldset>

	<fieldset><legend>To</legend>
		<div>
			<label for="new_location_id">Location ID</label>
			<input name="new_location_id" id="new_location_id" />
		</div>
		<div>
			<label for="location_search">Search Locations</label>
			<input name="location_search" id="location_search"
				value="<?php echo isset($_REQUEST['location_search']) ? View::escape($_REQUEST['location_search']) : ''; ?>" />
		</div>
		<?php
			if (isset($this->locationList)) {
				if (count($this->locationList)) {
					echo "
					<table>
						<tr><th></th>
							<th>Loc ID</th>
							<th>Status</th>
							<th>Address</th>
							<th>Town</th>
						</tr>
					";
					foreach ($this->locationList as $location) {
						// Don't display the old location in this list
						if ($location->getId() != $this->location->getId()) {
							$address = View::escape($location->getAddress());
							echo "
							<tr><td><input type=\"radio\" name=\"new_lid\"
										value=\"{$location->getId()}\" />
								</td>
								<td>{$location->getLocation_id()}</td>
								<td>{$location->getStatus()}</td>
								<td>$address</td>
								<td>{$location->getAddress()->getCity()}</td>
							</tr>
							";
						}
					}
					echo "</table>";
				}
				else {
					echo "<h3>No Locations Found</h3>";
				}
			}
		?>
		<button type="submit" class="submit">Move To Location</button>
	</fieldset>
</form>
