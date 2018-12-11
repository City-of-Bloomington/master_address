<?php
/**
 * @copyright 2009-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param array   $this->subunits
 * @param Address $this->address
 * @param boolean $this->disableButtons (optional)
 */
use Application\People\Person;
?>
<section>
    <header>
        <h2><?= $this->_(['subunit', 'subunits', count($this->subunits)]); ?></h2>
        <div class="tools">
        <?php
            if (isset($this->address)
                && !$this->disableButtons
                && parent::isAllowed('subunits', 'add')) {

                $h = $this->template->getHelper('buttonLink');
                echo $h->buttonLink(
                    parent::generateUri('subunits.add').'?address_id='.$this->address->id,
                    $this->_('subunit_add'),
                    'add'
                );
            }
        ?>
        </div>
    </header>
    <?php $this->_include('subunits/table.inc'); ?>
</section>
