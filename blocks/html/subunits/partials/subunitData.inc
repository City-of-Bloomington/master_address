<?php
/**
 * @copyright 2009-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param Subunit $this->subunit
 */
$fields = [
    'identifier',
    'notes',
    'state_plane_x',
    'state_plane_y',
    'latitude',
    'longitude',
    'usng',
    'status',
    'type_code',
    'type_name'
];
foreach ($fields as $f) {
    $$f = parent::escape($this->subunit->$f);
    echo "<tr><th>{$this->_($f)}</th><td>{$$f}</td></tr>";
}
