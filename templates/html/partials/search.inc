<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
$h = $this->getHelper('field');
$addressUri = parent::generateUri('addresses.index');
 $streetUri = parent::generateUri(  'streets.index');
?>
<div class="container">
    <form method="get" action="<?= $addressUri; ?>">
        <fieldset>
            <?php
                echo $h->field([
                    'name'  => 'address',
                    'id'    => 'address',
                    'label' => "<a href=\"$addressUri\">{$this->_(['address', 'addresses', 100])}</a>",
                    'value' => !empty($_GET['address']) ? parent::escape($_GET['address']) : null
                ]);
            ?>
            <button type="submit" class="search"><?= $this->_('search'); ?></button>
        </fieldset>
    </form>

    <form method="get" action="<?= $streetUri; ?>">
        <fieldset>
            <?php
                echo $h->field([
                    'name'  => 'street',
                    'id'    => 'street',
                    'label' => "<a href=\"$streetUri\">{$this->_(['street', 'streets', 100])}</a>",
                    'value' => !empty($_GET['street']) ? parent::escape($_GET['street']) : null
                ]);
            ?>
            <button type="submit" class="search"><?= $this->_('search'); ?></button>
        </fieldset>
    </form>
</div>
