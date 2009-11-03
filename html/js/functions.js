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
FRAMEWORK.getChangeLog = function (form,action)
{
	// Instantiate the Dialog
	changeLogForm = new YAHOO.widget.Dialog("changeLogForm",
		{
			fixedcenter:true,
			visible:false,
			constraintoviewport:true,
			postmethod:'none',
			buttons:[{text:"Submit",handler:changeLogSubmit,isDefault:true},
					{text:"Cancel",handler:changeLogCancel}]
		});
	changeLogForm.setHeader('Activity Log Entry');
	changeLogForm.setBody("<div><label for=\"changeLogEntry-action\">Action:</label>" + action +
		"<input type=\"hidden\" name=\"changeLogEntry[action]\" id=\"changeLogEntry-action\" value=\"" + action + "\" /></div>" +
		"<div><p>Enter comments or rationale for the activity log:</p><textarea name=\"changeLogEntry[notes]\" rows=\"4\" cols=\"50\"></textarea></div>" +
		"<div><label for=\"changeLogEntry-contact_id\" class=\"required\">Contact</label>" +
		"<select name=\"changeLogEntry[contact_id]\" id=\"changeLogEntry-contact_id\"><option></option>" +
		"</select></div>");

	changeLogForm.center();

	// Make sure to put this inside of whatever form called it.
	// That way the change log fields will be included in that form's post
	changeLogForm.render(form);
	changeLogForm.show();


	var callbacks = {
		// Successful XHR response handler
		success : function (o) {
			var messages = [];

			// Use the JSON Utility to parse the data returned from the server
			try {
				response = YAHOO.lang.JSON.parse(o.responseText);
				var contacts = response.contacts;
			}
			catch (x) {
				alert("Failed to load contacts!");
			}

			// The returned data was parsed into an array of objects.
			// Add a P element for each received message
			for (var i = 0, len = contacts.length; i < len; ++i) {
				var c = contacts[i];
				var option = document.createElement('option');
				option.setAttribute('value',c.id);
				option.appendChild(document.createTextNode(c.name));
				document.getElementById('changeLogEntry-contact_id').appendChild(option);
			}
		}
	};

	// Make the call to the server for JSON data
	YAHOO.util.Connect.asyncRequest('GET',"/master_address/contacts/home.php?format=json", callbacks);

	function changeLogSubmit() {
		if (document.getElementById('changeLogEntry-contact_id').selectedIndex > 0) {
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