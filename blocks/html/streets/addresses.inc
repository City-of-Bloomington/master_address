<?php
/**
 * @copyright 2018-2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl-3.0.txt GNU/AGPL, see LICENSE
 * @param string $this->street_id
 * @param array  $this->addresses
 */
declare (strict_types=1);
use Application\Url;

$min = 9999;
$max = 0;
foreach ($this->addresses as $a) {
    if ($a->street_number < $min) { $min = $a->street_number; }
    if ($a->street_number > $max) { $max = $a->street_number; }
}
?>
<section>
    <header>
        <h2><?= $this->_(['address', 'addresses', count($this->addresses)]); ?>
            <?= "($min - $max)"; ?>
        </h2>
    </header>
    <?php $this->_include('addresses/list.inc'); ?>
</section>
