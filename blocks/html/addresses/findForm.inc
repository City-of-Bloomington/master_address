<?php
/**
 * @copyright 2009-2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
?>
<form method="get" action="<?= parent::generateUri('addresses.index'); ?>">
    <fieldset><legend><?= $this->_('address'); ?></legend>
        <?php
            $h = $this->template->getHelper('field');
            echo $h->field([
                'name'  => 'address',
                'id'    => 'address',
                'label' => $this->_('address'),
                'value' => !empty($_GET['address']) ? parent::escape($_GET['address']) : null
            ]);
        ?>
    <button type="submit" class="search"><?= $this->_('search'); ?></button>
    </fieldset>
</form>
