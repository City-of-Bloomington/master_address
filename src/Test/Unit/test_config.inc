<?php
define('APPLICATION_NAME','master_address');

define('BASE_URI' , '/master_address');
define('BASE_HOST', 'localhost');
define('BASE_URL' , "http://".BASE_HOST.BASE_URI);

define('DATE_FORMAT',    'n/j/Y');
define('TIME_FORMAT',    'g:i a');
define('DATETIME_FORMAT', DATE_FORMAT.' '.TIME_FORMAT);
define('LOCALE', 'en_US');

define('PAGINATION_ITEMS_PER_PAGE', 20);

$DEFAULTS = [
    'city'            => 'Bloomington',
    'state'           => 'IN',
    'locationType_id' => 3
];
