<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl-3.0.txt GNU/AGPL, see LICENSE
 * @param array $this->addresses
 */
declare (strict_types=1);
?>
<section>
    <header>
        <h2><?= $this->_(['address', 'addresses', count($this->addresses)]); ?></h2>
    </header>
    <?php $this->_include('addresses/list.inc'); ?>
</section>
