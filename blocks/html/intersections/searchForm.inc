<?php
/**
 * @copyright 2015 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
$street = !empty($_REQUEST['street']) ? View::escape($_REQUEST['street']) : '';
?>
<h2>Intersections</h2>
<p>Find intersections for a street.</p>
<div id="findStreetForm" class="yui-skin-sam">
	<form method="get" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>">
		<fieldset><legend>Search</legend>
			<label for="street">Street</label>
			<div id="findStreetForm-autocomplete" style="width:15em;">
				<input name="street" id="street" value="<?= $street ; ?>" />
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

	var findStreetFormAC = new YAHOO.widget.AutoComplete("street",
														"container",
														streetService);
	findStreetFormAC.generateRequest = function(query) {
		return '?format=json;streetName=' + query;
	}

    return{
        streetService: streetService,
		findStreetFormAC: findStreetFormAC
    };
}();
</script>