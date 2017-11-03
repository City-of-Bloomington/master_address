<?php
/**
 * @copyright 2014-2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param Location $this->location
 */
$fields = [
    'id', 'status', 'type', 'mailable', 'occupiable', 'active', 'trashDay', 'recycleWeek'
];
foreach ($fields as $f) {
    $get = 'get'.ucfirst($f);
    $$f  = parent::escape($this->location->$get());
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
