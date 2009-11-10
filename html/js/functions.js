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

	// This is the popup for adding a new contact
	var addContactForm = new YAHOO.widget.Dialog("addContactForm",
		{
			fixedcenter:true,
			visible:false,
			constraintoviewport:true,
			postmethod:'none',
			buttons:[{text:"Submit",handler:addContact,isDefault:true},
					{text:"Cancel",handler:cancelAddContact}]
		});
	addContactForm.setHeader('Add A Contact');
	addContactForm.setBody("<form method=\"post\" action=\"" + BASE_URL + "/contacts/addContact.php\">" +
			"<fieldset><legend>Contact Info</legend><table>" +
			"<tr><td><label for=\"contact-last_name\" class=\"required\">Last Name</label></td>" +
				"<td><input name=\"contact[last_name]\" id=\"contact-last_name\" /></td></tr>" +
			"<tr><td><label for=\"contact-first_name\" class=\"required\">First Name</label></td>" +
				"<td><input name=\"contact[first_name]\" id=\"contact-first_name\" /></td></tr>" +
			"<tr><td><label for=\"contact-contact_type\" class=\"required\">Type</label></td>" +
				"<td><select name=\"contact[contact_type]\" id=\"contact-contact_type\">" +
				"</select></td></tr>" +
			"<tr><td><label for=\"contact-phone_number\" class=\"required\">Phone</label></td>" +
				"<td><input name=\"contact[phone_number]\" id=\"contact-phone_number\" /></td></tr>" +
			"<tr><td><label for=\"contact-agency\" class=\"required\">Organization</label></td>" +
				"<td><input name=\"contact[agency]\" id=\"contact-agency\" /></td></tr>" +
			"</table></fieldset></form>");
	addContactForm.center();
	addContactForm.render('content-panel');

	var callbacks = {
		success : function (o) {
			response = YAHOO.lang.JSON.parse(o.responseText);
			var types = response.types;
			for (i in types) {
				var option = document.createElement('option');
				option.appendChild(document.createTextNode(types[i]));
				document.getElementById('contact-contact_type').appendChild(option);
			}
		}
	};
	YAHOO.util.Connect.asyncRequest('GET',BASE_URL+'/contacts/types.php?format=json',callbacks);

	function checkRequiredContactFields() {
		if (document.getElementById('contact-last_name').value.trim()
			&& document.getElementById('contact-first_name').value.trim()
			&& document.getElementById('contact-phone_number').value.trim()
			&& document.getElementById('contact-agency').value.trim()) {
			return true;
		}
		alert('All the fields are required');
		return false;
	}
	function addContact() {
		if (checkRequiredContactFields()) {
			alert('We have all the required information');
		}
	}
	function cancelAddContact() {
		addContactForm.hide();
		addContactForm.destroy();
	}


	var manager = new YAHOO.widget.OverlayManager();
	manager.register([changeLogForm,addContactForm]);
	changeLogForm.show();

	function openAddContactForm() {
		addContactForm.show();
		addContactForm.focus();
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