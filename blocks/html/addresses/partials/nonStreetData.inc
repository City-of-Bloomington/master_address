<?php
/**
 * @copyright 2009-2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param Address $this->address
 */
declare (strict_types=1);
$fields = [
    'id'              => 'id',
    'type'            => 'address_type',
    'jurisdiction'    => 'jurisdiction_name',
    'plat'            => 'plat_name',
    'plat_lot_number' => 'plat_lot_number',
    'township'        => 'township_name',
    'section'         => 'section',
    'quarter'         => 'quarter_section'
];
$required = [
    'type', 'jurisdiction', 'section', 'township'
];
foreach ($fields as $k=>$f) {
    $$k = parent::escape($this->address->$f);
    $class = in_array($k, $required)
           ? $$k ? '' : ' class="missing"'
           : '';
    echo "<tr$class><th>{$this->_($k)}</th><td>{$$k}</td>";
}
