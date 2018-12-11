<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param array $this->addresses
 */
declare (strict_types=1);

echo "<addresses>";
foreach ($this->addresses as $a) {
    $this->address = $a;
    $this->_include('addresses/info.inc');
}
echo "</addresses>";
