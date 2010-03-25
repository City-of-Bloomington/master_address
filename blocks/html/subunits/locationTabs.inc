<?php
/**
 * @copyright 2009-2010 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Subunit $this->subunit
 */
$tabs = array();
$activeIndex = 0;
foreach ($this->subunit->getLocations() as $i=>$location) {
	$content = "
	<div id=\"location_{$location->getId()}\">
		<h4>Location Subunits</h4>
		<p><label>Location Status</label> <span>{$location->getStatus()}</span></p>
		<table>
			<thead>
			<tr><th>Address</th>
				<th>Active</th>
				<th>Status</th>
			</tr>
			</thead>
			<tbody>
	";
			foreach ($location->getSubunits() as $subunit) {
				$checked = '';
				if ($location->isActive($subunit)) {
					$checked = 'checked="checked"';
					$activeIndex = $i;
				}

				$content.= "
				<tr><td><a href=\"{$subunit->getURL()}\">{$subunit->getAddress()} $subunit</a></td>
					<td><input type=\"radio\" name=\"subunit_id\"
							value=\"{$subunit->getId()}\" $checked
							onchange=\"activateSubunit({$location->getId()},{$subunit->getId()});\" />
					</td>
					<td>{$subunit->getStatus()}</td>
				</tr>
				";
			}
	$content.= "
			</tbody>
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
<script type="text/javascript">
var activateSubunitFormAction = 'activate';
function handleActivateSubunit(form) {
	FRAMEWORK.getChangeLog(form,activateSubunitFormAction,'<?php echo BASE_URL; ?>');
}

function activateSubunit(location_id,subunit_id) {
	document.getElementById('location_id').value = location_id;
	document.getElementById('subunit_id').value = subunit_id;
	handleActivateSubunit(document.getElementById('activateSubunitForm'));
}

var locationTabs = new YAHOO.widget.TabView('locationTabs');
locationTabs.set('activeIndex',<?php echo $activeIndex; ?>);
</script>
<form id="activateSubunitForm" method="post"
		action="<?php echo BASE_URL; ?>/locations/activateSubunit.php"
		onsubmit="handleActivateSubunit(this); return false;">
	<fieldset>
		<input name="location_id" id="location_id" type="hidden" />
		<input name="subunit_id" id="subunit_id" type="hidden" />
	</fieldset>
</form>