<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Address $this->address
 */
$tabs = array();
$activeIndex = 0;
foreach ($this->address->getLocations(array('subunit_id'=>null)) as $i=>$location) {
	$buttons = '';
	if (userIsAllowed('Address')) {
		$buttons = "
		<div class=\"buttons\">
			<button type=\"button\">Add New Address</button>
			<button type=\"button\">Move to Existing Location</button>
			<button type=\"button\">Move Another Address to This Location</button>
		</div>
		";
	}
	$content = "
	<div id=\"#locationTab_{$location->getId()}\">
		<h1>Location Status {$location->getStatus()}</h1>
		$buttons
		<table>
			<tr><th>#</th>
				<th>Street</th>
				<th>Type</th>
				<th>Active</th>
				<th>Status</th>
				<th>Jurisdiction</th>
				<th>Zip</th>
			</tr>
	";
			foreach ($location->getLocations(array('subunit_id'=>null)) as $location) {
				$address = $location->getAddress();
				$active = $location->isActive() ? 'Y' : '';
				if ($active) { $activeIndex = $i; }

				$content.= "
				<tr><td>{$address->getStreet_number()}</td>
					<td>{$address->getStreet()->getStreetName()}</td>
					<td>{$address->getType()}</td>
					<td>$active</td>
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
	$tab = "
		<li><a href=\"#locationTab_{$location->getLocation_id()}\">
				<em>
				Location ID
				{$location->getLocation_id()}
				</em>
			</a>
		</li>
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
	<div class="yui-content">
	<?php
		foreach ($tabs as $tab) {
			echo $tab['content'];
		}
	?>
	</div>
</div>
<script type="text/javascript">
var locationTabs = new YAHOO.widget.TabView('locationTabs');
locationTabs.set('activeIndex',<?php echo $activeIndex; ?>);
</script>