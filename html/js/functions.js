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
		"<div><label for=\"changeLogEntry-contact_id\">Contact</label>" +
		"<select name=\"changeLogEntry[contact_id]\" id=\"changeLogEntry-contact_id\"><option></option>" +
		"</select></div>");

	changeLogForm.center();

	// Make sure to put this inside of whatever form called it.
	// That way the change log fields will be included in that form's post
	changeLogForm.render(form);
	changeLogForm.show();

	function changeLogSubmit() {
		form.submit();
	}
	function changeLogCancel() {
		changeLogForm.hide();
	}
}
FRAMEWORK.checkRequiredFields = function()
{
	var elements = document.getElementsByTagName("label");
	for (var i=0;i<elements.length;i++) {
		if (elements[i].className && elements[i].className =='required') {
			var id = elements[i].getAttribute('for');
			var obj = document.getElementById(id);
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