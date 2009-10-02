<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Address $this->address
 */
$tabs = array();
$activeIndex = 0;
foreach ($this->address->getLocations() as $i=>$location) {
	$buttons = '';
	if (userIsAllowed('Address')) {
		$addURL = new URL(BASE_URL.'/addresses/addAddress.php');
		$addURL->location_id = $location->getId();
		$addURL->street_id = $this->address->getStreet_id();

		$moveURL = new URL(BASE_URL.'/addresses/moveAddress.php');
		$moveURL->address_id = $this->address->getId();
		$moveURL->old_location_id = $location->getId();

		$buttons = "
		<div class=\"buttons\">
			<button type=\"button\" onclick=\"document.location.href='$addURL';\">
				Add New Address
			</button>
			<button type=\"button\" onclick=\"document.location.href='$moveURL';\">
				Move to Existing Location
			</button>
		</div>
		";
	}
	$content = "
	<div id=\"location_{$location->getId()}\">
		<h4 align=\"left\">Location Addresses</h4>
		<span style=\"float:right;\"><label>Location Status</label> <span>{$location->getStatus()}</span></span>
		$buttons
		<table>
			<thead>
			<tr><th>Address</th>
				<th>Active</th>
				<th>Status</th>
				<th>Jurisdiction</th>
				<th>Zip</th>
			</tr>
			</thead>
	";
			foreach ($location->getAddresses() as $address) {
				$checked = '';
				if ($location->isActive($address)) {
					$checked = 'checked="checked"';
					$activeIndex = $i;
				}

				$content.= "
				<tbody>
				<tr><td><a href=\"{$address->getURL()}\">$address</a></td>
					<td><input type=\"radio\" name=\"address_id\"
							value=\"{$address->getId()}\" $checked
							onchange=\"activateAddress({$location->getId()},{$address->getId()});\" />
					</td>
					<td>{$address->getStatus()}</td>
					<td>{$address->getJurisdiction()}</td>
					<td>{$address->getZip()}</td>
				</tr>
				</tbody>
				";
			}
	$content.= "
		</table>
	</div>
	";
	$tab = "
		<li><a href=\"#location_{$location->getId()}\">
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
<form id="activateAddressForm" method="post"
		action="<?php echo BASE_URL; ?>/locations/activateAddress.php"
		onsubmit="FRAMEWORK.getChangeLog(this,'activateAddressChangeLog'); return false;">
	<fieldset>
		<input name="location_id" id="location_id" type="hidden" />
		<input name="address_id" id="address_id" type="hidden" />
	</fieldset>
	<?php
		$block = new Block('changeLogs/changeLogEntryFields.inc',
							array('action'=>'activate','id'=>'activateAddressChangeLog'));
		echo $block->render('html');
	?>
</form>
<script type="text/javascript">
var locationTabs = new YAHOO.widget.TabView('locationTabs');
locationTabs.set('activeIndex',<?php echo $activeIndex; ?>);

function activateAddress(location_id,address_id) {
	document.getElementById('location_id').value = location_id;
	document.getElementById('address_id').value = address_id;
	FRAMEWORK.getChangeLog(document.getElementById('activateAddressForm'),
							'activateAddressChangeLog');
}
</script>