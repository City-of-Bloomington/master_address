<?php
/**
 * @copyright 2009-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param array $this->addresses
 */
?>
<section>
    <header><h1><?= $this->_('address_search'); ?></h1></header>

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
    <?php
        if (!$this->addresses) {
            if (isset($_GET['address'])) {
                echo sprintf($this->_('no_results', 'messages'), $this->_(['address', 'addresses', 10]));
            }
        }
        else {
            echo "
            <section>
                <header>
                    <h2>{$this->_(['address', 'addresses', count($this->addresses)])}</h2>
                </header>
            ";
                $this->_include('addresses/list.inc');
            echo "
            </section>
            ";
        }
    ?>
</section>
