<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Plat $this->plat
 */
$id = $this->plat ? $this->plat->getId() : '';
$name = $this->plat ? View::escape($this->plat->getName()) : '';
?>
<div>
	<span id="chosenPlat"><?php echo $name; ?></span>
	<input type="hidden" name="plat_id" id="plat_id" value="<?php echo $id; ?>" />
</div>
<div><button type="button" onclick="openPlatSelector();">Choose Plat</button></div>

<div id="platSelector" style="position:absolute; visibility:hidden;">
	<div class="hd">Choose a Plat</div>
	<div class="bd">
		<div id="findPlatForm-autosuggest" style="width:50ex;">
			<input name="plat[name]" id="plat-name"
					value="<?php echo $name; ?>" />
			<div id="plat-container"></div>
		</div>
	</div>
	<div class="ft">
		<button type="button" class="cancel" onclick="platSelector.hide();">
			Cancel
		</button>
	</div>
</div>
<script type="text/javascript">
var platSelector;
function openPlatSelector()
{
	platSelector = new YAHOO.widget.Overlay('platSelector');
	platSelector.center();
	platSelector.render();
	platSelector.show();
}

findPlatForm_autosuggest = function() {
	var platService = new YAHOO.util.XHRDataSource("<?php echo BASE_URL; ?>/plats/home.php");
	platService.responseType = YAHOO.util.XHRDataSource.TYPE_JSON;
	platService.responseSchema = {
		resultsList: "plats",
		fields: ["name","id","township"]
	};
	platService.maxCacheEntries = 5;

	var findPlatFormAC = new YAHOO.widget.AutoComplete("plat-name",
														"plat-container",
														platService);
	findPlatFormAC.resultTypeList = false;

	// Custom formatter of the results
	findPlatFormAC.formatResult = function(oResultData, sQuery, sResultMatch) {
	   var name = oResultData.name,
           township = oResultData.township;

		if (township) {
			township = township.charAt(0)+township.substr(1,township.length).toLowerCase();
			return name + " ("+township+")";
		}
		return name;
	};

	var myHandler = function(sType, aArgs) {
		var myAC = aArgs[0]; // reference back to the AC instance
		var elLI = aArgs[1]; // reference to the selected LI element
		var oData = aArgs[2]; // object literal of selected item's result data

		// update hidden form field with the selected item's ID
		document.getElementById('plat_id').value = oData.id;
		document.getElementById('chosenPlat').textContent = oData.name;
		platSelector.hide();
	};
	findPlatFormAC.itemSelectEvent.subscribe(myHandler);

	findPlatFormAC.generateRequest = function(query) {
		return '?format=json;plat[name]=' + query;
	}

	return {
		platService: platService,
		findPlatFormAC: findPlatFormAC
	};
}();
</script>