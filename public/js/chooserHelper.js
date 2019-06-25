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
        display.innerHTML = CHOOSER.displayValue(choice, options.type);
    }
};
