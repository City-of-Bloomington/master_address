/**
 * @copyright Copyright 2009 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
var FRAMEWORK = {};

/* A handy function for doing pop-up confirmations when deleting something */
FRAMEWORK.deleteConfirmation = function (url)
{
	if (confirm("Are you really sure you want to delete this?\n\nOnce deleted it will be gone forever."))
	{
		document.location.href = url;
		return true;
	}
	else { return false; }
};

var changeLogForm;
FRAMEWORK.getChangeLog = function (form,action,BASE_URL)
{

	// Instantiate the Dialog
	changeLogForm = new YAHOO.widget.Dialog("changeLogForm",
		{
			fixedcenter:true,
			visible:false,
			constraintoviewport:true,
			postmethod:'none',
			buttons:[{text:"Submit",handler:changeLogSubmit,isDefault:true},
					{text:"Cancel",handler:changeLogCancel},
					{text:"Add Contact",handler:openAddContactForm}]
		});
	function openAddContactForm() {
		window.open(BASE_URL + '/contacts/updateContact.php');
	}
	changeLogForm.setHeader('Activity Log Entry');
	changeLogForm.setBody("<div><label for=\"changeLogEntry-action\">Action:</label>" + action +
		"<input type=\"hidden\" name=\"changeLogEntry[action]\" id=\"changeLogEntry-action\" value=\"" + action + "\" /></div>" +
		"<div><p>Enter comments or rationale for the activity log:</p><textarea name=\"changeLogEntry[notes]\" rows=\"4\" cols=\"50\"></textarea></div>" +
		"<div><label for=\"changeLogEntry-contact_id\" class=\"required\">Contact</label>" +
		"<input type=\"hidden\" name=\"changeLogEntry[contact_id]\" id=\"changeLogEntry-contact_id\" />" +
		"<div id=\"changeLog-autocomplete\"><input name=\"chosenName\" id=\"chosenName\" />" +
		"<div id=\"changeLog-autocomplete-container\"></div></div>");

	changeLogForm.center();

	// Make sure to put this inside of whatever form called it.
	// That way the change log fields will be included in that form's post
	changeLogForm.render(form);
	changeLogForm.show();

	// Start the AutoComplete
    var contactService = new YAHOO.util.XHRDataSource(BASE_URL + '/contacts');
    contactService.responseType = YAHOO.util.XHRDataSource.TYPE_JSON;
    contactService.responseSchema = {
        resultsList: 'contacts',
        fields: ['id','name']
    };
    contactService.maxCacheEntries = 5;

	var autocomplete = new YAHOO.widget.AutoComplete('chosenName',
													'changeLog-autocomplete-container',
													contactService);
	// Custom formatter of the results
	autocomplete.resultTypeList = false;
	autocomplete.formatResult = function(result,query,match) {
		var name = result.name;
		return result.name;
	};

	autocomplete.generateRequest = function(query) {
		return '?format=json;name=' + query;
	}

	var autocompleteHandler = function(sType, aArgs) {
		var myAC = aArgs[0]; // reference back to the AC instance
		var elLI = aArgs[1]; // reference to the selected LI element
		var oData = aArgs[2]; // object literal of selected item's result data

		// update hidden form field with the selected item's ID
		document.getElementById('changeLogEntry-contact_id').value = oData.id;
		document.getElementById('chosenName').value = oData.name;
	};
	autocomplete.itemSelectEvent.subscribe(autocompleteHandler);

	function changeLogSubmit() {
		if (document.getElementById('changeLogEntry-contact_id').value) {
			form.submit();
		}
		else {
			alert('You must choose a Contact');
		}
	}
	function changeLogCancel() {
		changeLogForm.hide();
		changeLogForm.destroy();
	}
}

FRAMEWORK.checkRequiredFields = function()
{
	var elements = document.getElementsByTagName("label");
	for (i in elements) {
		if (elements[i].className && elements[i].className =='required') {
			var id = elements[i].getAttribute('for');
			var obj = document.getElementById(id);
			if (!obj) {
				alert('Failed to find the input id="' + id + '" for the required label. Please let a developer know about this problem');
				return false;
			}
			switch (obj.type) {
				case 'text':
					if (obj.value.trim() == "") {
						alert(elements[i].textContent+" is required");
						obj.focus();
						return false;
					}
				break;
				case 'select-one':
					if (obj.options[obj.selectedIndex].value.trim() == "") {
						alert(elements[i].textContent+" is required");
						obj.focus();
						return false;
					}
				break;
			}
		}
	}
	return true;
}

String.prototype.trim = function()
{
	return this.replace(/(?:(?:^|\n)\s+|\s+(?:$|\n))/g,"");
}
