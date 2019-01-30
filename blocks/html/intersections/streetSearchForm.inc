<?php
/**
 * @copyright 2015-2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param int    $this->street_id
 * @param string $this->street_name
 * @param array  $this->streets       Intersection results
 */
declare (strict_types=1);
use Application\Url;
?>
<section>
    <header>
        <h1><?= $this->_(['intersection', 'intersections', 10]); ?></h1>
    </header>

    <form method="get">
        <fieldset><legend><?= $this->_('street'); ?></legend>
        <?php
            $h = $this->template->getHelper('field');

            echo $h->field([
                'name'     => 'street_id',
                'id'       => 'street_id',
                'label'    => $this->_('street'),
                'type'     => 'chooser',
                'chooser'  => 'street',
                'value'    => $this->street_id,
                'display'  => $this->street_name,
                'required' => true
            ]);
        ?>
            <button type="submit" class="search"><?= $this->_('search'); ?></button>
        </fieldset>
    </form>
    <?php
        if ($this->street_id && !$this->streets) {
            echo '<div>';
            echo sprintf($this->_('no_results', 'messages'), $this->_(['street', 'streets', 2]));
            echo '</div>';
        }

        if ($this->streets) {
            $this->_include('streets/list.inc');
        }
    ?>
</section>
