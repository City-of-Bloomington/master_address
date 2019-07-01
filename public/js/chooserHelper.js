"use strict";
/**
 * Javascript for the Chooser Helper
 *
 * @see templates/html/helpers/Chooser.php
 */
var CHOOSER_HELPER = {
    /**
     * @param object choice   The data object, chosen by the user
     * @param object options  Contains element_id and type
     *
     * options will contain:
     *     element_id: The ID of the form input
     *     type:       Type of chooser (address, street, etc.)
     *
     * @see templates/html/helpers/Chooser.php
     */
    handleChoice: function (choice, options) {
        let input   = document.getElementById(options.element_id),
            display = document.getElementById(options.element_id + '-display');

        input.value = choice.id;
        switch (options.type) {
            case 'address'   : display.innerHTML = choice.streetAddress; break;
            case 'street'    : display.innerHTML = choice.streetName;    break;
            case 'streetName': display.innerHTML = choice.streetName;    break;
            case 'person'    : display.innerHTML = choice.fullname;      break;
            case 'plat'      : display.innerHTML = choice.name + ', ' + choice.township_name; break;
        }
    }
};
