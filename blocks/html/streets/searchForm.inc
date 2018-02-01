<?php
/**
 * @copyright 2009-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param array $this->streets
 */
?>
<section>
    <header>
        <h1><?= $this->_('street_search'); ?></h1>
    </header>
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
    <?php
        if (!count($this->streets)) {
            if (isset($_GET['street'])) {
                echo sprintf($this->_('no_results', 'messages'), $this->_(['street', 'streets', 2]));
            }
        }
        else {
            $this->_include('streets/list.inc');
        }
    ?>
</section>
