"use strict";
/**
 * Opens a modal dialog, letting the user search for and choose something
 *
 * @see templates/html/helpers/Chooser.php
 *
 * @copyright 2013-2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt, see LICENSE
 */
var STREET_CHOOSER = {
    resultsDiv: {},
    results: [],
    callback: {},
    chooserDiv: document.getElementById('chooser'),


    /**
     * Initiate a new modal instance for the chooser
     *
     * @param function callback Function to call once the user has chosen an address
     * @param object   options  Storage for custom parameters for the instance
     */
    start: function (callback, options) {
        let modal = document.getElementById('modal-container');
        if (!modal) { modal = STREET_CHOOSER.createModal(); }

        STREET_CHOOSER.callback = function (data) {
            callback(data, options);
            STREET_CHOOSER.destroy();
        };

        STREET_CHOOSER.startStreetChooser(document.getElementById('chooser'), options);
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
                      + '    <button type="button" onclick="STREET_CHOOSER.destroy();">Cancel</button>'
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
    startStreetChooser: function (target, options) {
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
        STREET_CHOOSER.resultsDiv = document.getElementById('searchResults');
        document.getElementById('streetQuery').focus();
        document.getElementById('streetSearchForm').addEventListener('submit', function (e) {
            e.preventDefault();
            STREET_CHOOSER.searchStreet(document.getElementById('streetQuery').value);
        }, false);

        if (options) { STREET_CHOOSER.applyDefaultSearch(options); }
    },

    /**
     * Prepopulate the search form
     *
     * @param object options  Instance parameters to populate from
     */
    applyDefaultSearch: function (options) {
        let submit = document.createEvent('Event');


        if (options.streetQuery) {
            submit.initEvent('submit', true, true);
            document.getElementById('streetQuery').value = options.streetQuery;
            document.getElementById('streetSearchForm').dispatchEvent(submit);
        }
    },

    /**
     * Perform an async search request
     *
     * @param string street  The street name
     */
    searchStreet: function (street) {
        let req = new XMLHttpRequest();

        req.addEventListener('load', STREET_CHOOSER.resultsHandler);
        req.open('GET', ADDRESS_SERVICE + '/streets?format=json;street=' + street);
        req.send();
    },

    /**
     * Draws the search results into the modal div
     */
    resultsHandler: function (event) {
        STREET_CHOOSER.results = [];

        try { STREET_CHOOSER.results = JSON.parse(event.target.responseText); }
        catch (e) { STREET_CHOOSER.resultsDiv.innerHTML = e.message; }

        if (STREET_CHOOSER.results.length) {
            STREET_CHOOSER.resultsDiv.innerHTML = '';
            STREET_CHOOSER.resultsDiv.appendChild(STREET_CHOOSER.resultsToHTML(STREET_CHOOSER.results));
        }
        else {
            STREET_CHOOSER.resultsDiv.innerHTML = 'No results found';
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
            li.innerHTML       = row.streetName;
            li.addEventListener('click', STREET_CHOOSER.choose, false);
            ul.appendChild(li);
        });
        return ul;
    },

    /**
     * Handler for when a user chooses on of the results
     */
    choose: function (event) {
        STREET_CHOOSER.resultsDiv.innerHTML = '';
        STREET_CHOOSER.callback(STREET_CHOOSER.results[event.target.dataset.index]);
    }
};

