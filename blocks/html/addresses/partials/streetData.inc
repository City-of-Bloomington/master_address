<?php
/**
 * @copyright 2009-2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param Address $this->address
 */
$fields = [
	'streetNumberPrefix', 'streetNumber', 'streetNumberSuffix', 'street_id',
	'city', 'zip', 'zipplus4', 'status'
];
foreach ($fields as $f) {
	$get = 'get'.ucfirst($f);
	$$f = parent::escape($this->address->$get());
	echo "<tr><th>{$this->_($f)}</th><td>{$$f}</td></tr>";
}
