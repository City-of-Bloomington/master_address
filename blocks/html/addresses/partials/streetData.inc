<?php
/**
 * @copyright 2009-2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param Address $this->address
 */
$fields = [
	'street_id', 'city', 'zip', 'zipplus4'
];
foreach ($fields as $f) {
    $$f = parent::escape($this->address->$f);
	echo "<tr><th>{$this->_($f)}</th><td>{$$f}</td></tr>";
}
