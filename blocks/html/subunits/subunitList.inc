<?php
/**
 * @copyright 2009-2014 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param SubunitList $this->subunitList
 * @param Address $this->address
 */
?>
<div class="interfaceBox subunitList">
<h1>Subunits</h1>
<?php
	if (isset($this->address) && userIsAllowed('Subunit') && !$this->deactivateButtons) {
		echo "
		<div>
			<button type=\"button\" onclick=\"document.location.href='".BASE_URL."/subunits/addSubunit.php?street_address_id={$this->address->getId()}';\">
				Add Subunit
			</button>
		</div>
		";
	}
	if (count($this->subunitList)) {
		echo "
		<table>
			<thead>
				<tr><th>Type</th>
					<th>#</th>
					<th>Status</th>
					<th>Lat, Long</th>
					<th>State Plane x, y</th>
					<th>USNG</th>
				</tr>
			</thead>
			<tbody>
		";
			foreach ($this->subunitList as $subunit) {
				$number = View::escape($subunit->getIdentifier());
				echo "
				<tr><td>{$subunit->getType()}</td>
					<td><a href=\"{$subunit->getURL()}\">$number</a></td>
					<td>{$subunit->getStatus()}</td>
					<td>{$subunit->getLatLong()}</td>
					<td>{$subunit->getState_plane_xy_coordinate()}</td>
					<td>{$subunit->getUsng_coordinate()}</td>
				</tr>
				";
			}
		echo "
			</tbody>
		</table>
		";
	}
?>
</div>
