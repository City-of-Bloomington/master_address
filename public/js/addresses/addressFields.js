"use strict";
/**
 * Javascript for forms using the addressFields.inc partial
 *
 * @see blocks/html/addresses/partials/addressFields.inc
 */
(function () {
    let updateCityState = function (e) {
        document.getElementById('city'        ).value     = e.target.options[e.target.selectedIndex].dataset.city;
        document.getElementById('city-display').innerText = e.target.options[e.target.selectedIndex].dataset.city;
    };
    document.getElementById('zip').addEventListener('change', updateCityState, false);
})();
