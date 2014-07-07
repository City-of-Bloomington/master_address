<?php
/**
 * @copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param Street $this->street
 */
?>
<div id="streetSelector" style="position:absolute; visibility:hidden;">
	<div class="hd">Search for a street:</div>
	<div class="bd">
		<form id="dialog" method="get" action="<?php echo BASE_URL; ?>/streets/home.php">
			<input name="format" type="hidden" value="json" />
			<label for="streetName">Street Name:</label><input type="text" name="streetName" id="streetName" />
		</form>
		<div id="streetSelectorResults"></div>
	</div>
</div>

<script type="text/javascript">
var streetSelector;
var streetSelectorCancel = function() {
    this.cancel();
}
var streetSelectorSubmit = function() {
    this.submit();
}
function setStreet(id,name) {
	document.getElementById('street_id').value = id;
	document.getElementById('chosenStreetName').innerHTML = '';
	document.getElementById('chosenStreetName').appendChild(document.createTextNode(name));
	streetSelector.hide();
}
var updateStreetSelectorResults = function(o) {
	var result = YAHOO.lang.JSON.parse(o.responseText),
		list = document.createElement('ul');

	document.getElementById('streetSelectorResults').innerHTML = '';

	for (i in result.streets) {
		var id   = result.streets[i].id,
			name = result.streets[i].name,
			town = result.streets[i].town,
			a  = document.createElement('a'),
			li = document.createElement('li');

		a.appendChild(document.createTextNode(name + ', ' + town));
		a.setAttribute('onclick',"setStreet(" + id + ",'" + name + "');");

		li.appendChild(a);
		list.appendChild(li);
	}
	document.getElementById('streetSelectorResults').appendChild(list);
}
var onFailure = function(o) {
    alert("Your submission failed. Status: " + o.status);
}
function openStreetSelector() {
	streetSelector = new YAHOO.widget.Dialog("streetSelector",
									{ 	buttons:[ { text:"Submit", handler:streetSelectorSubmit, isDefault:true },
												{ text:"Cancel", handler:streetSelectorCancel }],
										hideaftersubmit:false,
										context:['chosenStreetName','tl','bl']
									});
	streetSelector.center();
	streetSelector.render();
	streetSelector.show();
	streetSelector.callback.success = updateStreetSelectorResults;
	streetSelector.callback.failure = onFailure;
	streetSelector.hideaftersubmit = false;
}
</script>
