/**
 * Opens a modal dialog, letting the user search for and choose something
 *
 * @see templates/html/helpers/Chooser.php
 *
 * @copyright 2013-2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt, see LICENSE
 */
var ADDRESS_CHOOSER = {
    resultsDiv: {},
    results: [],
    callback: {},
    chooserDiv: document.getElementById('chooser'),
    chosenAddressIndex: 0,


    /**
     * Initiate a new modal instance for the chooser
     *
     * @param function callback Function to call once the user has chosen an address
     * @param object   options  Storage for custom parameters for the instance
     */
    start: function (callback, options) {
        let modal = document.getElementById('modal-container');
        if (!modal) { modal = ADDRESS_CHOOSER.createModal(); }

        ADDRESS_CHOOSER.callback = function (data) {
            callback(data, options);
            ADDRESS_CHOOSER.destroy();
        };

        ADDRESS_CHOOSER.startAddressChooser(document.getElementById('chooser'), options);
    },

    destroy: function () {
        document.body.removeChild(document.getElementById('modal-container'));
    },

    createModal: function () {
        // Create the outer element using createElement, so we can
        // append the element to the document body.
        let div = document.createElement('DIV');
        div.setAttribute('id', 'modal-container');
        div.setAttribute('class', 'modal');
        div.innerHTML = '<div>'
                      + '    <div id="chooser"></div>'
                      + '    <button type="button" onclick="ADDRESS_CHOOSER.destroy();">Cancel</button>'
                      + '</div>';
        document.body.appendChild(div);
        return div;
    },

    /**
     * Draw the HTML searchForm into the target DIV
     *
     * @param Element target  The DOM element to draw the chooser into
     * @param object  options Instance parameters for the modal dialog
     */
    startAddressChooser: function (target, options) {
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
        ADDRESS_CHOOSER.resultsDiv = document.getElementById('searchResults');
        document.getElementById('addressQuery').focus();
        document.getElementById('addressSearchForm').addEventListener('submit', function (e) {
            e.preventDefault();
            ADDRESS_CHOOSER.searchAddress(document.getElementById('addressQuery').value);
        }, false);

        if (options) {
            ADDRESS_CHOOSER.applyDefaultSearch(options);
        }
    },

    /**
     * Prepopulate the search form
     *
     * @param object options  Instance parameters to populate from
     */
    applyDefaultSearch: function (options) {
        let submit = document.createEvent('Event');


        if (options.addressQuery) {
            submit.initEvent('submit', true, true);
            document.getElementById('addressQuery').value = options.addressQuery;
            document.getElementById('addressSearchForm').dispatchEvent(submit);
        }
    },

    /**
     * Perform an async search request
     *
     * @param string address
     */
    searchAddress: function (address) {
        let req = new XMLHttpRequest();

        req.addEventListener('load', ADDRESS_CHOOSER.resultsHandler);
        req.open('GET', ADDRESS_SERVICE + '/addresses?format=json;address=' + address);
        req.send();
    },

    /**
     * Draws the search results into the modal div
     */
    resultsHandler: function (event) {
        ADDRESS_CHOOSER.results = [];

        try { ADDRESS_CHOOSER.results = JSON.parse(event.target.responseText); }
        catch (e) { ADDRESS_CHOOSER.resultsDiv.innerHTML = e.message; }

        if (ADDRESS_CHOOSER.results.length) {
            ADDRESS_CHOOSER.resultsDiv.innerHTML = '';
            ADDRESS_CHOOSER.resultsDiv.appendChild(ADDRESS_CHOOSER.resultsToHTML(ADDRESS_CHOOSER.results));
        }
        else {
            ADDRESS_CHOOSER.resultsDiv.innerHTML = 'No results found';
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
     * @return Element
     */
    resultsToHTML: function (results) {
        let ul = document.createElement('UL'),
            li;

        results.forEach(function (row, i, array) {
            li                 = document.createElement('LI');
            li.dataset.index   = i;
            li.innerHTML       = row.streetAddress;
            li.addEventListener('click', ADDRESS_CHOOSER.chooseAddress, false);
            ul.appendChild(li);
        });
        return ul;
    },

    /**
     * Handler for when a user chooses on of the results
     */
    chooseAddress: function (event) {
        ADDRESS_CHOOSER.resultsDiv.innerHTML = '';
        ADDRESS_CHOOSER.chosenAddressIndex   = event.target.dataset.index;

        ADDRESS_CHOOSER.addressInfo(ADDRESS_CHOOSER.results[ADDRESS_CHOOSER.chosenAddressIndex], ADDRESS_CHOOSER.checkForSubunit);
    },

    /**
     * Look up detailed address information
     *
     * @param object   address  An address search result
     * @param function f        Callback function to return the information to
     */
    addressInfo: function (address, f) {
        let req = new XMLHttpRequest();

        req.addEventListener('load', f);
        req.open('GET', ADDRESS_SERVICE + '/addresses/' + address.id + '?format=json');
        req.send();
    },

    checkForSubunit: function (event) {
        let address;

        try {
            address = JSON.parse(event.target.responseText);
            if (address.subunits.length) {
                ADDRESS_CHOOSER.chosenAddress        = address;
                ADDRESS_CHOOSER.resultsDiv.innerHTML = '';
                ADDRESS_CHOOSER.resultsDiv.appendChild(ADDRESS_CHOOSER.subunitsToHTML(address.subunits));
            }
            else {
                ADDRESS_CHOOSER.callback(address.address);
            }
        }
        catch (e) {
            ADDRESS_CHOOSER.callback(ADDRESS_CHOOSER.results[ADDRESS_CHOOSER.chosenAddressIndex]);
        }
    },

    /**
     * Creates the HTML for choosing a subunit
     *
     * Returns an Element node to ready to be appended to the DOM.
     * Each subunit will have an eventListener already attached.
     *
     * Pass in the subunits array from an addressInfo request
     *
     * @param array subunits  An array of Subunit objects
     */
    subunitsToHTML: function (subunits) {
        let table = document.createElement('TABLE'),
            tr;
        table.innerHTML = '<caption>Choose a subunit</caption>';

        subunits.forEach(function (subunit, i, array) {
            tr           = document.createElement('TR');
            tr.innerHTML = '<td data-index="' + i + '">'
                         +  subunit.type_code +' '+ subunit.identifier
                         + '</td>';
            tr.addEventListener('click', ADDRESS_CHOOSER.chooseSubunit, false);
            table.appendChild(tr);
        });
        return table;
    },

    /**
     * Handler for when a user chooses one of the subunits
     *
     * Adds the chosen subunit to the address info in the results variable.
     * Then, calls the callback function, passing the result address
     */
    chooseSubunit: function (event) {
        ADDRESS_CHOOSER.resultsDiv.innerHTML = '';

        // Add the subunit information to the address
        ADDRESS_CHOOSER.results[ADDRESS_CHOOSER.chosenAddressIndex].subunit = ADDRESS_CHOOSER.chosenAddress.subunits[event.target.dataset.index];

        ADDRESS_CHOOSER.callback(ADDRESS_CHOOSER.results[ADDRESS_CHOOSER.chosenAddressIndex]);
    }
};
