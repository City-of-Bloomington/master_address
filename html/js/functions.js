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
FRAMEWORK.getChangeLog = function (form,div)
{
	changeLogForm = new YAHOO.widget.Overlay(div);
	changeLogForm.center();
	changeLogForm.render();
	changeLogForm.show();
}
FRAMEWORK.checkRequiredFields = function()
{
	var elements = document.getElementsByTagName("label");
	for(var i=0;i<elements.length;i++){
		if(elements[i].className && elements[i].className =='required'){
			var id = elements[i].getAttribute('for');
			var obj = document.getElementById(id);
			switch(obj.type){
				case 'text':
					if(obj.value.length() == 0){
						alert(id+" is required");
						obj.focus();
						return false;
					}
				break;				
				case 'select-one':
					if(obj.options[obj.selectedIndex].value == ""){
						alert(id+" is required");
						obj.focus();
						return false;
					}
				break;	
			}
		}
	}
	return true;	
}	
