"use strict";
/**
 * Opens a modal dialog, letting the user search for and choose something
 *
 * @see templates/html/helpers/Chooser.php
 *
 * @copyright 2013-2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt, see LICENSE
 */
var PERSON_CHOOSER = {
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
        if (!modal) { modal = PERSON_CHOOSER.createModal(); }

        PERSON_CHOOSER.callback = function (data) {
            callback(data, options);
            PERSON_CHOOSER.destroy();
        };

        PERSON_CHOOSER.startPersonChooser(document.getElementById('chooser'), options);
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
                      + '    <button type="button" onclick="PERSON_CHOOSER.destroy();">Cancel</button>'
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
    startPersonChooser: function (target, options) {
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
        PERSON_CHOOSER.resultsDiv = document.getElementById('searchResults');
        document.getElementById('sf').focus();
        document.getElementById('peopleSearchForm').addEventListener('submit', function (e) {
            e.preventDefault();
            PERSON_CHOOSER.searchPerson(
                document.getElementById('sf').value,
                document.getElementById('sl').value,
                document.getElementById('se').value
            );
        }, false);

        if (options) { PERSON_CHOOSER.applyDefaultSearch(options); }
    },

    /**
     * Prepopulate the search form
     *
     * @param object options  Instance parameters to populate
     */
    applyDefaultSearch: function (options) {
        let submit = document.createEvent('Event');

        if (options.sf) { document.getElementById('sf').value = options.sf; }
        if (options.sl) { document.getElementById('sl').value = options.sl; }
        if (options.se) { document.getElementById('se').value = options.se; }

        if (options.sf || options.sl || options.se) {
            submit.initEvent('submit', true, true);
            document.getElementById('peopleSearchForm').dispatchEvent(submit);
        }
    },

    /**
     * Perform an async search request
     *
     * @param string firstname
     * @param string lastname
     * @param string email
     */
    searchPerson: function (firstname, lastname, email) {
        let req = new XMLHttpRequest();

        req.addEventListener('load', PERSON_CHOOSER.resultsHandler);
        req.open('GET', ADDRESS_SERVICE + '/people?format=json'
                                        + ';firstname=' + firstname
                                        + ';lastname='  + lastname
                                        + ';email='     + email);
        req.send();
    },

    /**
     * Draws the search results into the modal div
     */
    resultsHandler: function (event) {
        PERSON_CHOOSER.results = [];

        try { PERSON_CHOOSER.results = JSON.parse(event.target.responseText); }
        catch (e) { PERSON_CHOOSER.resultsDiv.innerHTML = e.message; }

        if (PERSON_CHOOSER.results.length) {
            PERSON_CHOOSER.resultsDiv.innerHTML = '';
            PERSON_CHOOSER.resultsDiv.appendChild(PERSON_CHOOSER.resultsToHTML(PERSON_CHOOSER.results));
        }
        else {
            PERSON_CHOOSER.resultsDiv.innerHTML = 'No results found';
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
            li.innerHTML       = row.fullname;
            li.addEventListener('click', PERSON_CHOOSER.choose, false);
            ul.appendChild(li);
        });
        return ul;
    },

    /**
     * Handler for when a user chooses on of the results
     */
    choose: function (event) {
        PERSON_CHOOSER.resultsDiv.innerHTML = '';
        PERSON_CHOOSER.callback(PERSON_CHOOSER.results[event.target.dataset.index]);
    }
};
