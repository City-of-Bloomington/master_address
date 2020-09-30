# Locations Service

This service searches for locations in master address.  Each address and subunit gets a separate location, so this is equivalent to listing base addresses and subunits in the same query.

The search uses wildcards for some of the address component parameters.  The rest of the parameters use an exact match.

The database code for the search function is in:
https://github.com/City-of-Bloomington/master_address/blob/master/src/Application/Locations/PdoLocationsRepository.php

## Endoint
GET https://[Master Address URL]/locations

## Query Parameters
Parameters should be url encoded and provided in the URL of the request.

### location
The "location" parameter is an address string that you want to search.  This string will be parsed into the address component parameters listed below.

/locations?location=410+W+4th
is equivalent to:
/locations?street_number=410;direction=W;street_name=4th

https://github.com/City-of-Bloomington/master_address/blob/master/src/Domain/Addresses/UseCases/Parse/ParseResponse.php

### address component parameters
The parameters come from the parse and represent parts of the address string

param name | match type | Example
---------- | ---------- | -------
street_number_prefix | exact match | `where street_number_prefix='U'`
street_number        | This will always be an integer. But we do the search as a wildcard string | `where street_number like '13%'`
street_number_suffix | exact match | `where street_number_suffix='1/2'`
direction            | exact match | Possible values: N, S, E, W
street_name          | Wildcard match | `where street_name like 'Wal%'`
streetType           | exact match of type codes | `where street_type_code='RD'`
postDirection        | exact match | Possible values: N, S, E, W
subunitType          | exact match of subunit types | `where subunit_type_code='APT'`
subunitIdentifier    | Wildcard match | `where subunit_identifer like '?%'`
city                 | exact match |
state                | exact match | Two letter state code. Indiana (IN) is the only valid value
zip                  | Wildcard match | `where zip like '47%'`
zipplus4             | Wildcard match | `where zipplus4 like '32%'`


### ID fields
These are ID fields from the foreign key tables.

param name | Database field
---------- | --------------
location_id | locations.location_id
address_id  | addresses.id
subunit_id  | subunits.id

### Location Flags
These are all boolean flags in the locations table.  The possible values for 0,1 where 0=false and 1=true.

param name    |
----------    |
mailable      |
occupiable    |
group_quarter |
active        |


### Status fields
Possible values are: (current, retired, proposed, duplicate, temporary).

param name     | Status
----------     | ------
status         | Location status
address_status | Address status
subunit_status | Subunit status

### Address Type
param name   | Possible values
----------   | ---------------
address_type | Facility, Property, Street, Temporary, Utility

### Location Type

param name   | description
----------   | -----------
type_id      | Location type ID
type_code    | Location type abbreviation
type_name    | Location type full name

The possible values are:

 id | code | name
--- | ---- | ----
  1 | AG   | Agricultural
  2 | SL   | Sub Location
  3 | CM   | Commercial
  4 | RS   | Residential Single Family
  5 | ED   | Educational
  6 | GV   | Government
  7 | ID   | Industrial
  8 | MD   | Medical
  9 | MU   | Mixed Use
 10 | IN   | Other Non-Profit or Institutional
 11 | PR   | Property Parcel
 12 | RG   | Religious
 13 | TM   | Temporary
 14 | UT   | Utility
 15 | UK   | Unknown
 16 | RM   | Residential Multi-Family
 17 | R2   | Residential 2 Family
 18 | AS   | Accessory Structure
