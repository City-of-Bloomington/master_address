<?php
/**
 * @copyright 2009-2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
use Application\Models\Addresses\Parser;
?>
<form method="get">
    <fieldset><legend><?= $this->_('search'); ?></legend>
        <?php
            $h = $this->template->getHelper('field');
            echo $h->field([
                'name'  => 'street',
                'id'    => 'street',
                'label' => $this->_('street'),
                'value' => !empty($_GET['street']) ? parent::escape($_GET['street']) : null
            ]);
        ?>
        <button type="submit" class="search"><?= $this->_('search'); ?></button>
    </fieldset>
</form>
