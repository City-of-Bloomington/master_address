<?php
/**
 * @copyright 2014-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param Location $this->location
 */
$fields = [
    'location_id', 'status', 'type_code', 'mailable', 'occupiable', 'active', 'trash_day', 'recycle_week'
];
foreach ($fields as $f) {
    $$f  = parent::escape($this->location->$f);
}
?>
<table>
    <thead>
        <tr><?php foreach ($fields as $f) { echo "<th>{$this->_($f)}</th>"; } ?></tr>
    </thead>
    <tbody>
        <tr><?php foreach ($fields as $f) { echo "<td>{$$f}</td>"; } ?></tr>
    </tbody>
</table>
