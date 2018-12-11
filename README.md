# Master Address

Web application for managing addressing for a city

## System Requirements

## Install

### Ansible

## Developers

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

### Choosers

Urls that support choosing need to support passing in:
* callback_field
* callback_url

These generally are search forms that display results of things.  The urls are usually the same urls as the general purpose search.  The only difference is that if the callback_field and callback_url are passed in (via REQUEST parameters), then the results will link to the callback_url instead of the regular URL to that thing.

### Terminology
* Find   - queries that do exact matching of fields
* search - queries that do pattern matching of fields
