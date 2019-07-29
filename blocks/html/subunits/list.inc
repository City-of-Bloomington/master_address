<?php
/**
 * @copyright 2009-2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param array   $this->subunits
 */
use Application\People\Person;
?>
<section>
    <header>
        <h2><?= $this->_(['subunit', 'subunits', count($this->subunits)]); ?></h2>
    </header>
    <?php $this->_include('subunits/table.inc'); ?>
</section>
