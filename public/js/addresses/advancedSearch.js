"use strict";
/**
 * Javascript for the address advanced search form
 *
 * @copyright 2013-2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt, see LICENSE
 */
(function () {
    const start  = document.getElementById('block_start'  ),
          end    = document.getElementById('block_end'    ),
          number = document.getElementById('street_number');

    let updateFields = function (e) {
        if (e.target.getAttribute('id') == 'street_number') {
            start.value = '';
              end.value = '';
        }
        else {
            number.value = '';
        }
    };

    number.addEventListener('change', updateFields, false);
     start.addEventListener('change', updateFields, false);
       end.addEventListener('change', updateFields, false);
})();
