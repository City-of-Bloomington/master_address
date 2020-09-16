# Master Address

Web application for managing addressing for a city

## Choosers

Master Address hosts javascript choosers for:

* addresses
* streets
* plats
* streetNames
* people (requires authentication)

Some example clients are available in the [Tests](https://github.com/City-of-Bloomington/master_address/tree/master/src/Test/Choosers).

To use one of these choosers in a web application:

* Declare CSS for the modal chooser
* load JS environment variables from Master Address
* load JS chooser from Master Address

Sometimes, you might want to pre-populate the search inputs when spawning the modal.  (For example: with a previous search entry).  In this case, in the options, you can pass parameters with the same name as the input IDs in the modal dialog.

```html
<html>
<head>
    <style type="text/css">
        #modal-container {
            position: fixed; top:50%; left:50%;
            transform: translate(-50%, -50%);
            z-index:100;

            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
        }
        #modal-container div { background-color: white; }
        #modal-container #searchResults ul { list-style-type: none; }
        #modal-container #searchResults li { cursor: pointer; }
    </style>
    <script src="https://bloomington.in.gov/master_address/js/choosers/env.php"></script>
    <script src="https://bloomington.in.gov/master_address/js/choosers/streetChooser.js"></script>
    <script>
        function handleStreetChoice(streetChoice) {
            document.getElementById('street_id').value = streetChoice.id;
            document.getElementById('street'   ).value = streetChoice.streetName;
        }
    </script>

</head>
<body>
    <form>
        <fieldset>
            <input name="street"    id="street"    />
            <input name="street_id" id="street_id" />

            <button type="button" class="search"
                onclick="STREET_CHOOSER.start(handleStreetChoice, {streetQuery: document.getElementById('street').value});">
                Choose a street
            </button>
        </fieldset>
    </form>
</body>
</html>
```

## Developer Notes

When inserting or updating rows with null values, the database default values will not be applied if you explicitly provide a data array with a field set to null.  To get the database to apply the default value, you must leave null values completely out of the data array.

For example: the state column is not null default 'IN'.  But that will not get applied if you explicitly set it to null, instead of leaving it out of the data.

```php
# This will fail with a database error of "not null field (state) set to null"
$data = [
    'street_number'  => 12,
    'address_type'   => 'STREET',
    'street_id'      => 1,
    'jursdiction_id' => 1,
    'state'          => null
];
$address_id = parent::saveToTable($data, 'addresses');

# This will correctly allow the database to apply the default value
$data = [
    'street_number'  => 12,
    'address_type'   => 'STREET',
    'street_id'      => 1,
    'jursdiction_id' => 1
    # State left out
];
$address_id = parent::saveToTable($data, 'addresses');

```

### Terminology
* Find   - queries that do exact matching of fields
* search - queries that do pattern matching of fields
