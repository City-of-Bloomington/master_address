<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param array  $this->names
 */
declare (strict_types=1);
?>
<section>
    <header>
        <h1><?= $this->_('streetName_search'); ?></h1>
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
        if (parent::isAllowed('streetNames', 'add')) {
            $h = $this->template->getHelper('buttonLink');
            echo $h->buttonLink(
                parent::generateUri('streetNames.add'),
                $this->_('streetName_add'),
                'add'
            );
        }

        if (!$this->names) {
            if (isset($_GET['street'])) {
                echo sprintf($this->_('no_results', 'messages'), $this->_(['streetName', 'streetNames', 2]));
            }
        }
        else {
            $this->_include('streets/names/list.inc');
        }
    ?>
</section>
