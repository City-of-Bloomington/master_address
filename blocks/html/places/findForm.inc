<?php
/**
 * @copyright 2021 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param array  $this->places
 *
 * All Request object properties are available as template vars and are already escaped
 * @see Domain\Places\Actions\Search\Request
 */
declare (strict_types=1);
$title = $this->_(['place', 'places', 100]);
?>
<section>
    <form method="get">
        <fieldset>
            <legend><?= $this->_('place_search'); ?></legend>
            <?php
                $h = $this->template->getHelper('field');
                echo $h->field([
                    'name' => 'name',
                    'id'   => 'name',
                    'label' => $this->_('name'),
                    'value' => $this->name
                ]);
            ?>
            <button type="submit" class="search"><?= $this->_('search'); ?></button>
        </fieldset>
    </form>
    <?php
        if ($this->places) {
            $this->_include('places/list.inc');
        }
        else {
            echo sprintf($this->_('no_results', 'messages'), $title);
        }
    ?>
</section>
