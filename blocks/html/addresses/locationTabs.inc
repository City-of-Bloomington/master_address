<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Address $this->address
 */
$tabs = array();
foreach ($this->address->getLocations(array('subunit_id'=>null)) as $location) {
	$tab = "
		<li><a href=\"#locationTab_{$location->getLocation_id()}\">
				Location ID
				{$location->getLocation_id()}
			</a>
		</li>
	";
	$content = "
	<div id=\"#locationTab_{$location->getId()}\">
		<h1>Location Addresses</h1>
		<div class=\"buttons\">
			<button type=\"button\">Add New Address</button>
			<button type=\"button\">Move to Existing Location</button>
			<button type=\"button\">Move Another Address to This Location</button>
		</div>
		<table>
			<tr><th>#</th>
				<th>Street</th>
				<th>Type</th>
				<th>Status</th>
				<th>Jurisdiction</th>
				<th>Zip</th>
			</tr>
	";
			foreach ($location->getLocations(array('subunit_id'=>null)) as $location) {
				$address = $location->getAddress();
				$content.= "
				<tr><td>{$address->getStreet_number()}</td>
					<td>{$address->getStreet()->getStreetName()}</td>
					<td>{$address->getType()}</td>
					<td>{$address->getStatus()}</td>
					<td>{$address->getJurisdiction()}</td>
					<td>{$address->getZip()}</td>
				</tr>
				";
			}
	$content.= "
		</table>
	</div>
	";
	$tabs[] = array('tab'=>$tab,'content'=>$content);
}
?>
<div id="locationTabs" class="yui-navset">
	<ul class="yui-nav">
	<?php
		foreach ($tabs as $tab) {
			echo $tab['tab'];
		}
	?>
	</ul>
	<div class="yui-nav">
	<?php
		foreach ($tabs as $tab) {
			echo $tab['content'];
		}
	?>
	</div>
</div>
<script type="text/javascript">
var locationTabs = new YAHOO.widget.TabView('locationTabs');
</script>