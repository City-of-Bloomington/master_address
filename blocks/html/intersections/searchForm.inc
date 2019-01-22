<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param string $this->street_1_id
 * @param string $this->street_1_name
 * @param string $this->street_2_id
 * @param string $this->street_2_name
 * @param array  $this->intersections
 */
declare (strict_types=1);
use Application\Url;
?>
<section>
    <header>
        <h1><?= $this->_(['intersection', 'intersections', 10]); ?></h1>
    </header>

    <form method="get">
        <fieldset>
        <?php
            $h = $this->template->getHelper('field');

            echo $h->field([
                'name'     => 'street_id_1',
                'id'       => 'street_id_1',
                'label'    => $this->_('street'),
                'type'     => 'chooser',
                'chooser'  => 'street',
                'value'    => $this->street_1_id,
                'display'  => $this->street_1_name,
                'required' => true
            ]);

            echo $h->field([
                'name'     => 'street_id_2',
                'id'       => 'street_id_2',
                'label'    => $this->_('street'),
                'type'     => 'chooser',
                'chooser'  => 'street',
                'value'    => $this->street_2_id,
                'display'  => $this->street_2_name,
                'required' => true
            ]);
        ?>
            <button type="submit" class="search"><?= $this->_('search'); ?></button>
        </fieldset>
    </form>
    <?php
        if ($this->street_1_id && $this->street_2_id) {
            if ($this->intersections) {
                $this->_include('intersections/list.inc');
            }
            else {
                echo sprintf($this->_('no_results', 'messages'), $this->_(['intersection', 'intersections', 10]));
            }
        }
    ?>
</section>
