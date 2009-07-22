<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
?>
<div id="findStreetForm" class="yui-skin-sam">
	<form method="get" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>">
		<fieldset><legend>Search Streets</legend>
			<label for="streetName">Street Name</label>
			<div id="findStreetForm-autocomplete" style="width:15em;">
				<input name="streetName" id="streetName"
						value="<?php echo isset($_GET['streetName']) ? View::escape($_GET['streetName']) : ''; ?>" />
				<div id="container"></div>
			</div>
			<button type="submit" class="search">Search</button>
		</fieldset>
	</form>

</div>

<script type="text/javascript">
findStreetForm_autosuggest = function() {
    var streetService = new YAHOO.util.XHRDataSource("<?php echo BASE_URL; ?>/streets/home.php");
    streetService.responseType = YAHOO.util.XHRDataSource.TYPE_JSON;
    streetService.responseSchema = {
        resultsList: "streets",
        fields: ["name"]
    };
    streetService.maxCacheEntries = 5;

	var findStreetFormAC = new YAHOO.widget.AutoComplete("streetName",
														"container",
														streetService);
	findStreetFormAC.generateRequest = function(query) {
		return '?format=json;streetName=' + query;
	}

    return {
        streetService: streetService,
		findStreetFormAC: findStreetFormAC
    };
}();
</script>