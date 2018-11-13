"use strict";
/**
 * Opens a popup window letting the user search for and choose a person
 *
 * To use this script the HTML elements must have the correct IDs so
 * we can update those elements when the callback is triggered.
 * You then register the CHOOSER.open function as the onclick handler,
 * passing in the fieldId you are using for your inputs elements.
 *
 * Here is the minimal HTML required:
 * <input id="{$fieldId}" value="" />
 * <span  id="{$fieldId}-display"></span>
 * <a onclick="CHOOSER.open(event, '$fieldId');">Change Person</a>
 *
 * Example as it would appear in the final HTML:
 * <input id="reportedByPerson_id" value="" />
 * <span  id="reportedByPerson-display"></span>
 * <a onclick="CHOOSER.open(event 'reportedByPerson_id');">Change Person</a>
 *
 * @see templates/html/helpers/Chooser.php
 *
 * @copyright 2013-2018 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt, see LICENSE
 */
var CHOOSER = {
	fieldId: '',
	popup: {},
	open: function (e, fieldId, url) {
        var popupUrl = new URL(url),
            params   = popupUrl.searchParams;

        e.preventDefault();
        e.stopPropagation();

        params.append('popup', 1);
        params.append('callback', 'CHOOSER.set');

		CHOOSER.fieldId = fieldId;
		CHOOSER.popup   = window.open(
            popupUrl.toString(),
			'popup',
			'menubar=no,location=no,status=no,toolbar=no,width=800,height=600,resizeable=yes,scrollbars=yes'
		);
        return false;
	},
	set: function (value, display) {
        document.getElementById(CHOOSER.fieldId).value = value;
        document.getElementById(CHOOSER.fieldId + '-display').innerHTML = display;
        CHOOSER.popup.close();
	}
}
