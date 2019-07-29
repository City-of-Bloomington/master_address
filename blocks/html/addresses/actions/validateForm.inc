<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param string  $this->query   The address string from the user
 * @param Address $this->address The validated address object
 */
declare (strict_types=1);
?>
<section>
    <header>
        <h1><?= $this->_('validate'); ?></h1>
        <p><?= $this->_('addresses.validate', 'messages'); ?></p>
    </header>
    <form method="get">
        <fieldset>
            <?php
                $h = $this->template->getHelper('field');
                echo $h->field([
                    'name' => 'query',
                    'id'   => 'query',
                    'label' => $this->_('address'),
                    'value' => $this->query
                ]);
            ?>
            <button type="submit"><?= $this->_('submit'); ?></button>
        </fieldset>
    </form>
    <?php
        if ($this->address) {
            $this->disableButtons = true;
            $this->_include('addresses/info.inc');
        }
    ?>
</section>
