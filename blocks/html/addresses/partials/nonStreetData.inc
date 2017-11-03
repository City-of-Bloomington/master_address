<?php
/**
 * @copyright 2009-2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param Address $this->address
 */
declare (strict_types=1);
$fields = [
    'type', 'jurisdiction', 'plat', 'platLotNumber', 'township', 'section', 'quarterSection',
    'x', 'y', 'latitude', 'longitude', 'USNG'
];
$required = [
    'type', 'jurisdiction', 'section', 'township'
];
foreach ($fields as $f) {
    $get   = 'get'.ucfirst($f);
    $$f    = parent::escape($this->address->$get());
    $class = in_array($f, $required)
           ? $$f ? '' : ' class="missing"'
           : '';
    echo "<tr$class><th>{$this->_($f)}</th><td>{$$f}</td>";
}
