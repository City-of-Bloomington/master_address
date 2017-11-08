<?php
/**
 * @copyright 2009-2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param Subunit $this->subunit
 */
use Application\Models\Subunits\Subunit;

foreach (Subunit::$fields as $f) {
    $get = 'get'.ucfirst($f);
    $$f  = parent::escape($this->subunit->$get());
    echo "<tr><th>{$this->_($f)}</th><td>{$$f}</td></tr>";
}
