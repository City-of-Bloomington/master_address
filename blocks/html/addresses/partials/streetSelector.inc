<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Street $this->street
 */
$id = '';
$name = '';
if (isset($this->street)) {
	$id = $this->street->getId();
	$name = View::escape($this->street->getStreetName());
}
?>
<div>
	<span id="chosenStreetName"><?php echo $name ?></span>
	<input type="hidden" name="street_id" id="street_id" value="<?php echo $id; ?>" />
	<button type="button" onclick="openStreetSelector();">Choose Street</button>
</div>

<div id="streetSelector" style="position:absolute; visibility:hidden;">
	<div class="hd">Choose a Street</div>
	<div class="bd">
		<div id="findStreetForm-autosuggest" style="width:15em;">
			<input name="streetName" id="streetName" value="<?php echo $name; ?>" />
			<div id="street-container"></div>
		</div>
	</div>
	<div class="ft">
		<button type="button" class="cancel" onclick="streetSelector.hide();">
			Cancel
		</button>
	</div>
</div>

<script type="text/javascript">
var streetSelector;
function openStreetSelector() {
	streetSelector = new YAHOO.widget.Overlay('streetSelector');
	streetSelector.center();
	streetSelector.render();
	streetSelector.show();
}

findStreetForm_autosuggest = function() {
	var streetService = new YAHOO.util.XHRDataSource("<?php echo BASE_URL; ?>/streets/home.php");
	streetService.responseType = YAHOO.util.XHRDataSource.TYPE_JSON;
	streetService.responseSchema = {
		resultsList: "streets",
		fields: ["name","id","town"]
	};
	streetService.maxCacheEntries = 5;

	var findStreetFormAC = new YAHOO.widget.AutoComplete("streetName",
														"street-container",
														streetService);
	findStreetFormAC.resultTypeList = false;

	// Custom formatter of the results
	findStreetFormAC.formatResult = function(oResultData, sQuery, sResultMatch) {
	   var name = oResultData.name,
           town = oResultData.town;

		if (town) {
			town = town.charAt(0)+town.substr(1,town.length).toLowerCase();
			return name + " ("+town+")";
		}
		return name;
	};

	var myHandler = function(sType, aArgs) {
		var myAC = aArgs[0]; // reference back to the AC instance
		var elLI = aArgs[1]; // reference to the selected LI element
		var oData = aArgs[2]; // object literal of selected item's result data

		// update hidden form field with the selected item's ID
		document.getElementById('street_id').value = oData.id;
		document.getElementById('chosenStreetName').textContent = oData.name;
		streetSelector.hide();
	};
	findStreetFormAC.itemSelectEvent.subscribe(myHandler);

	findStreetFormAC.generateRequest = function(query) {
		return '?format=json;streetName=' + query;
	}

	return {
		streetService: streetService,
		findStreetFormAC: findStreetFormAC
	};
}();
</script>