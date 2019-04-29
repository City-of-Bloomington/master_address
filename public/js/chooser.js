"use strict";
/**
 * Opens a popup window letting the user search for and choose something
 *
 * To use this script the HTML elements must have the correct IDs so
 * we can update those elements when the callback is triggered.
 * You then register the CHOOSER.start function as the onclick handler,
 * passing in the fieldId you are using for your inputs elements, and the type
 * of chooser you want (address, street, person, etc.)
 *
 * Here is the minimal HTML required:
 * <input id="{$fieldId}" value="" />
 * <span  id="{$fieldId}-display"></span>
 * <button onclick="CHOOSER.start('$fieldId', 'address');">Change Address</button>
 *
 * Example as it would appear in the final HTML:
 * <input id="reportedByPerson_id" value="" />
 * <span  id="reportedByPerson-display"></span>
 * <button onclick="CHOOSER.start('reportedByPerson_id', 'person');">Change Person</button>
 *
 * @see templates/html/helpers/Chooser.php
 *
 * @copyright 2013-2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt, see LICENSE
 */
"use strict";
var CHOOSER = {
    /**
     * Initiate a new modal instance for the chooser
     *
     * @param string id   ID attribute of the input we're choosing for
     * @param string type Chooser type (address, person, street, etc. )
     */
    start: function (id, type) {
        let modal = document.getElementById('modal-container');
        if (!modal) { modal = CHOOSER.createModal(); }

        window.startChooser(
            document.getElementById('chooser'),
            function (data) {
                document.getElementById(id).value = data.id;
                document.getElementById(id + '-display').innerHTML = CHOOSER.displayValue(data, type);
                CHOOSER.destroy();
            },
            type
        );
    },
    destroy: function () {
        document.body.removeChild(document.getElementById('modal-container'));
    },
    displayValue: function (data, type) {
        switch (type) {
            case 'address'   : return data.streetAddress; break;
            case 'street'    : return data.streetName;    break;
            case 'streetName': return data.streetName;    break;
            case 'person'    : return data.fullname;      break;
            case 'plat'      : return data.name + ', ' + data.township_name; break;
        }
    },
    createModal: function () {
        // Create the outer element using createElement, so we can
        // append the element to the document body.
        let div = document.createElement('DIV');
        div.setAttribute('id', 'modal-container');
        div.setAttribute('class', 'modal');
        div.innerHTML = '<div>'
                      + '    <div id="chooser"></div>'
                      + '    <button type="button" onclick="CHOOSER.destroy();">Cancel</button>'
                      + '</div>';
        document.body.appendChild(div);
        return div;
    }
};

(function (window) {
    let resultsDiv, // HTMLElement to draw the choose into
        results,    // Variable to store search result data
        callback,   // Function to call when once the user makes a choice
        chooserType,// Type of chooser (address | person | street )

        /**
         * Writes the chooser into an HTML element.
         *
         * Calls the callback function with the chosen data once the user
         * chooses something.
         *
         * @param Element  target  The DOM element to draw the chooser into
         * @param function call    Function to call with the chosen data
         * @param string   type    Type of chooser to start (address | street | person)
         */
        startChooser = function (target, call, type) {
            callback    = call;
            chooserType = type;

            switch (type) {
                case 'address'   : startAddressChooser   (target); break;
                case 'person'    : startPersonChooser    (target); break;
                case 'plat'      : startPlatChooser      (target); break;
                case 'street'    : startStreetChooser    (target); break;
                case 'streetName': startStreetNameChooser(target); break;
            }
        },

        /**
         * Draw the HTML searchForm into the target DIV
         *
         * @param Element target  The DOM element to draw the chooser into
         */
        startAddressChooser = function (target) {
            target.innerHTML = '<form method="get" id="addressSearchForm">'
                             + '    <fieldset><legend>Search</legend>'
                             + '        <div>'
                             + '            <label  for="addressQuery">Address</label>'
                             + '            <input name="addressQuery" id="addressQuery" />'
                             + '        </div>'
                             + '        <button type="submit" class="search">Search</button>'
                             + '        <div id="searchResults"></div>'
                             + '    </fieldset>'
                             + '</form>';
            resultsDiv = document.getElementById('searchResults');
            document.getElementById('addressQuery').focus();
            document.getElementById('addressSearchForm').addEventListener('submit', function (e) {
                e.preventDefault();
                searchAddress(document.getElementById('addressQuery').value);
            }, false);
        },

        /**
         * Draw the HTML searchForm into the target DIV
         *
         * @param Element target  The DOM element to draw the chooser into
         */
        startPersonChooser = function (target) {
            target.innerHTML = '<form method="get" id="peopleSearchForm">'
                             + '    <fieldset><legend>Find Someone</legend>'
                             + '        <div>'
                             + '            <label  for="sf">Firstname</label>'
                             + '            <input name="sf" id="sf" />'
                             + '        </div>'
                             + '        <div>'
                             + '            <label for="sl">Last Name</label>'
                             + '            <input name="sl" id="sl" />'
                             + '        </div>'
                             + '        <div>'
                             + '            <label for="se">Email</label>'
                             + '            <input name="se" id="se" />'
                             + '        </div>'
                             + '        <button type="submit" class="search">Search</button>'
                             + '        <div id="searchResults"></div>'
                             + '    </fieldset>'
                             + '</form>';
            resultsDiv = document.getElementById('searchResults');
            document.getElementById('sf').focus();
            document.getElementById('peopleSearchForm').addEventListener('submit', function (e) {
                e.preventDefault();
                searchPerson(
                    document.getElementById('sf').value,
                    document.getElementById('sl').value,
                    document.getElementById('se').value
                );
            }, false);
        },

        /**
         * Draw the HTML searchForm into the target DIV
         *
         * @param Element target  The DOM element to draw the chooser into
         */
        startPlatChooser = function (target) {
            target.innerHTML = '<form method="get" id="platSearchForm">'
                             + '    <fieldset><legend>Find Plat</legend>'
                             + '        <div>'
                             + '            <label  for="pn">Name</label>'
                             + '            <input name="pn" id="pn" />'
                             + '        </div>'
                             + '        <button type="submit" class="search">Search</button>'
                             + '        <div id="searchResults"></div>'
                             + '    </fieldset>'
                             + '</form>';
            resultsDiv = document.getElementById('searchResults');
            document.getElementById('pn').focus();
            document.getElementById('platSearchForm').addEventListener('submit', function (e) {
                e.preventDefault();
                searchPlat(document.getElementById('pn').value);
            }, false);
        },

        /**
         * Draw the HTML searchForm into the target DIV
         *
         * @param Element target  The DOM element to draw the chooser into
         */
        startStreetChooser = function (target) {
            target.innerHTML = '<form method="get" id="streetSearchForm">'
                             + '    <fieldset><legend>Search</legend>'
                             + '        <div>'
                             + '            <label  for="streetQuery">Street</label>'
                             + '            <input name="streetQuery" id="streetQuery" />'
                             + '        </div>'
                             + '        <button type="submit" class="search">Search</button>'
                             + '        <div id="searchResults"></div>'
                             + '    </fieldset>'
                             + '</form>';
            resultsDiv = document.getElementById('searchResults');
            document.getElementById('streetQuery').focus();
            document.getElementById('streetSearchForm').addEventListener('submit', function (e) {
                e.preventDefault();
                searchStreet(document.getElementById('streetQuery').value);
            }, false);
        },

        /**
         * Draw the HTML searchForm into the target DIV
         *
         * @param Element target  The DOM element to draw the chooser into
         */
        startStreetNameChooser = function (target) {
            target.innerHTML = '<form method="get" id="streetNameSearchForm">'
                             + '    <fieldset><legend>Search</legend>'
                             + '        <div>'
                             + '            <label  for="sn">Street Name</label>'
                             + '            <input name="sn" id="sn" />'
                             + '        </div>'
                             + '        <button type="submit" class="search">Search</button>'
                             + '        <div id="searchResults"></div>'
                             + '    </fieldset>'
                             + '</form>';
            resultsDiv = document.getElementById('searchResults');
            document.getElementById('sn').focus();
            document.getElementById('streetNameSearchForm').addEventListener('submit', function (e) {
                e.preventDefault();
                searchStreetName(document.getElementById('sn').value);
            }, false);
        },

        searchAddress = function (address) {
            let req = new XMLHttpRequest();

            req.addEventListener('load', resultsHandler);
            req.open('GET', ADDRESS_SERVICE + '/addresses?format=json;address=' + address);
            req.send();
        },

        searchPerson = function (firstname, lastname, email) {
            let req = new XMLHttpRequest();

            req.addEventListener('load', resultsHandler);
            req.open('GET', ADDRESS_SERVICE + '/people?format=json'
                                            + ';firstname=' + firstname
                                            + ';lastname='  + lastname
                                            + ';email='     + email);
            req.send();
        },

        searchPlat = function (name) {
            let req = new XMLHttpRequest();

            req.addEventListener('load', resultsHandler);
            req.open('GET', ADDRESS_SERVICE + '/plats?format=json;name=' + name);
            req.send();
        },

        searchStreet = function (street) {
            let req = new XMLHttpRequest();

            req.addEventListener('load', resultsHandler);
            req.open('GET', ADDRESS_SERVICE + '/streets?format=json;street=' + street);
            req.send();
        },

        searchStreetName = function (street) {
            let req = new XMLHttpRequest();

            req.addEventListener('load', resultsHandler);
            req.open('GET', ADDRESS_SERVICE + '/streets/names?format=json;street=' + street);
            req.send();
        },

        /**
         * Draws the search results into the modal div
         */
        resultsHandler = function (event) {
            results = [];

            try { results = JSON.parse(event.target.responseText); }
            catch (e) { resultsDiv.innerHTML = e.message; }

            if (results.length) {
                resultsDiv.innerHTML = '';
                resultsDiv.appendChild(resultsToHTML(results));
            }
            else {
                resultsDiv.innerHTML = 'No results found';
            }
        },

        /**
         * Creates the HTML for the search results
         *
         * Returns an Element node to ready to be appended to the DOM.
         * This creates an unordered list with eventListeners attached.
         *
         * Search results should come in as an array of objects.
         *
         * For each search result, we store the array index as a data
         * attribute.  Later on, you should be able to use that index
         * to pull the object data from the results variable.
         *
         * @param  array       Search result data
         * @return ELement
         */
        resultsToHTML = function (results) {
            let ul = document.createElement('UL'),
                li;

            results.forEach(function (row, i, array) {
                li                 = document.createElement('LI');
                li.dataset.index   = i;
                li.innerHTML       = CHOOSER.displayValue(row, chooserType);
                li.addEventListener('click', choose, false);
                ul.appendChild(li);
            });
            return ul;
        },

        /**
         * Handler for when a user chooses on of the results
         */
        choose = function (event) {
            resultsDiv.innerHTML = '';
            callback(results[event.target.dataset.index]);
        };

    window.startChooser = startChooser;
})(window);
