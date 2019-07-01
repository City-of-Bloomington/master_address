"use strict";
/**
 * Opens a modal dialog, letting the user search for and choose something
 *
 * @see templates/html/helpers/Chooser.php
 *
 * @copyright 2013-2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt, see LICENSE
 */
var STREETNAME_CHOOSER = {
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
        if (!modal) { modal = STREETNAME_CHOOSER.createModal(); }

        STREETNAME_CHOOSER.callback = function (data) {
            callback(data, options);
            STREETNAME_CHOOSER.destroy();
        };

        STREETNAME_CHOOSER.startStreetNameChooser(document.getElementById('chooser'));
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
                      + '    <button type="button" onclick="STREETNAME_CHOOSER.destroy();">Cancel</button>'
                      + '</div>';
        document.body.appendChild(div);
        return div;
    },

    /**
     * Draw the HTML searchForm into the target DIV
     *
     * @param Element target  The DOM element to draw the chooser into
     */
    startStreetNameChooser: function (target) {
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
        STREETNAME_CHOOSER.resultsDiv = document.getElementById('searchResults');
        document.getElementById('sn').focus();
        document.getElementById('streetNameSearchForm').addEventListener('submit', function (e) {
            e.preventDefault();
            STREETNAME_CHOOSER.searchStreetName(document.getElementById('sn').value);
        }, false);
    },

    searchStreetName: function (street) {
        let req = new XMLHttpRequest();

        req.addEventListener('load', STREETNAME_CHOOSER.resultsHandler);
        req.open('GET', ADDRESS_SERVICE + '/streets/names?format=json;street=' + street);
        req.send();
    },

    /**
     * Draws the search results into the modal div
     */
    resultsHandler: function (event) {
        STREETNAME_CHOOSER.results = [];

        try { STREETNAME_CHOOSER.results = JSON.parse(event.target.responseText); }
        catch (e) { STREETNAME_CHOOSER.resultsDiv.innerHTML = e.message; }

        if (STREETNAME_CHOOSER.results.length) {
            STREETNAME_CHOOSER.resultsDiv.innerHTML = '';
            STREETNAME_CHOOSER.resultsDiv.appendChild(STREETNAME_CHOOSER.resultsToHTML(STREETNAME_CHOOSER.results));
        }
        else {
            STREETNAME_CHOOSER.resultsDiv.innerHTML = 'No results found';
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
            li.addEventListener('click', STREETNAME_CHOOSER.choose, false);
            ul.appendChild(li);
        });
        return ul;
    },

    /**
     * Handler for when a user chooses on of the results
     */
    choose: function (event) {
        STREETNAME_CHOOSER.resultsDiv.innerHTML = '';
        STREETNAME_CHOOSER.callback(STREETNAME_CHOOSER.results[event.target.dataset.index]);
    }
};
