<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Street $this->street
 */
?>
<div id="findStreetForm-autosuggest" style="width:15em;">
	<input type="hidden" name="street_id" id="street_id"
			value="<?php echo $this->street->getStreet_id(); ?>" />
	<input name="streetName" id="streetName"
			value="<?php echo View::escape($this->street->getStreetName()); ?>" />
	<div id="container"></div>
</div>
<script type="text/javascript">
findStreetForm_autosuggest = function() {
	var streetService = new YAHOO.util.XHRDataSource("<?php echo BASE_URL; ?>/streets/home.php");
	streetService.responseType = YAHOO.util.XHRDataSource.TYPE_JSON;
	streetService.responseSchema = {
		resultsList: "streets",
		fields: ["name","id","town"]
	};
	streetService.maxCacheEntries = 5;

	var findStreetFormAC = new YAHOO.widget.AutoComplete("streetName",
														"container",
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