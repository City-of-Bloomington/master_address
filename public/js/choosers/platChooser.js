"use strict";
/**
 * Opens a modal dialog, letting the user search for and choose something
 *
 * @see templates/html/helpers/Chooser.php
 *
 * @copyright 2013-2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt, see LICENSE
 */
var PLAT_CHOOSER = {
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
        if (!modal) { modal = PLAT_CHOOSER.createModal(); }

        PLAT_CHOOSER.callback = function (data) {
            callback(data, options);
            PLAT_CHOOSER.destroy();
        };

        PLAT_CHOOSER.startPlatChooser(document.getElementById('chooser'));
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
                      + '    <button type="button" onclick="PLAT_CHOOSER.destroy();">Cancel</button>'
                      + '</div>';
        document.body.appendChild(div);
        return div;
    },

    /**
     * Draw the HTML searchForm into the target DIV
     *
     * @param Element target  The DOM element to draw the chooser into
     */
    startPlatChooser: function (target) {
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
        PLAT_CHOOSER.resultsDiv = document.getElementById('searchResults');
        document.getElementById('pn').focus();
        document.getElementById('platSearchForm').addEventListener('submit', function (e) {
            e.preventDefault();
            PLAT_CHOOSER.searchPlat(document.getElementById('pn').value);
        }, false);
    },

    searchPlat: function (name) {
        let req = new XMLHttpRequest();

        req.addEventListener('load', PLAT_CHOOSER.resultsHandler);
        req.open('GET', ADDRESS_SERVICE + '/plats?format=json;name=' + name);
        req.send();
    },

    /**
     * Draws the search results into the modal div
     */
    resultsHandler: function (event) {
        PLAT_CHOOSER.results = [];

        try { PLAT_CHOOSER.results = JSON.parse(event.target.responseText); }
        catch (e) { PLAT_CHOOSER.resultsDiv.innerHTML = e.message; }

        if (PLAT_CHOOSER.results.length) {
            PLAT_CHOOSER.resultsDiv.innerHTML = '';
            PLAT_CHOOSER.resultsDiv.appendChild(PLAT_CHOOSER.resultsToHTML(PLAT_CHOOSER.results));
        }
        else {
            PLAT_CHOOSER.resultsDiv.innerHTML = 'No results found';
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
            li.innerHTML       = row.name + ', ' + row.township_name;
            li.addEventListener('click', PLAT_CHOOSER.choose, false);
            ul.appendChild(li);
        });
        return ul;
    },

    /**
     * Handler for when a user chooses on of the results
     */
    choose: function (event) {
        PLAT_CHOOSER.resultsDiv.innerHTML = '';
        PLAT_CHOOSER.callback(PLAT_CHOOSER.results[event.target.dataset.index]);
    }
};
