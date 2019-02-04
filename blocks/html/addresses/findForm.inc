<?php
/**
 * @copyright 2009-2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param array  $this->addresses  Array of Address entities
 * @param bool   $this->searching  Whether a search request was submitted or not
 *
 * @param string $this->street_number_prefix
 * @param string $this->street_number
 * @param string $this->street_number_suffix
 * @param string $this->street_direction
 * @param string $this->street_name
 * @param string $this->street_post_direction
 * @param string $this->street_suffix_code
 * @param string $this->block_start
 * @param string $this->block_end
 * @param string $this->city
 * @param string $this->zip
 * @param array  $this->directions  Options to populate dropdown
 * @param array  $this->cities      Options to populate dropdown
 * @param array  $this->streetTypes
 */
$cities      = [['value'=>'']];
$directions  = [['value'=>'']];
$streetTypes = [['value'=>'']];
foreach ($this->cities      as $c) { $cities     [] = ['value'=>$c]; }
foreach ($this->directions  as $d) { $directions [] = ['value'=>$d]; }
foreach ($this->streetTypes as $t) { $streetTypes[] = ['value'=>$t['id'], 'label'=>$t['code']]; }

$h = $this->template->getHelper('field');
?>
<section id="addressSearch">
    <header><h1><?= $this->_(['address', 'addresses', 100]); ?></h1></header>
    <details><summary><?= $this->_('search_advanced'); ?></summary>
        <form method="get">
            <fieldset><legend><?= $this->_('block'); ?></legend>
            <?php
                echo $h->field([
                    'name'    => 'block_start',
                    'id'      => 'block_start',
                    'label'   => $this->_('start'),
                    'value'   => $this->block_start
                ]);
                echo $h->field([
                    'name'    => 'block_end',
                    'id'      => 'block_end',
                    'label'   => $this->_('end'),
                    'value'   => $this->block_end
                ]);

            ?>
            </fieldset>
            <fieldset><legend><?= $this->_('address'); ?></legend>
            <?php
                echo $h->field([
                    'name'    => 'street_number_prefix',
                    'id'      => 'street_number_prefix',
                    'label'   => $this->_('street_number_prefix'),
                    'value'   => $this->street_number_prefix
                ]);
                echo $h->field([
                    'name'    => 'street_number',
                    'id'      => 'street_number',
                    'label'   => $this->_('street_number'),
                    'value'   => $this->street_number
                ]);
                echo $h->field([
                    'name'    => 'street_number_suffix',
                    'id'      => 'street_number_suffix',
                    'label'   => $this->_('street_number_suffix'),
                    'value'   => $this->street_number_suffix
                ]);
            ?>
            </fieldset>
            <fieldset><legend><?= $this->_('street'); ?></legend>
            <?php
                echo $h->field([
                    'name'    => 'street_direction',
                    'id'      => 'street_direction',
                    'label'   => $this->_('street_direction'),
                    'value'   => $this->street_direction,
                    'type'    => 'select',
                    'options' => $directions
                ]);
                echo $h->field([
                    'name'    => 'street_name',
                    'id'      => 'street_name',
                    'label'   => $this->_('street_name'),
                    'value'   => $this->street_name
                ]);
                echo $h->field([
                    'name'    => 'street_post_direction',
                    'id'      => 'street_post_direction',
                    'label'   => $this->_('street_post_direction'),
                    'value'   => $this->street_post_direction,
                    'type'    => 'select',
                    'options' => $directions
                ]);
                echo $h->field([
                    'name'    => 'street_suffix_code',
                    'id'      => 'street_suffix_code',
                    'label'   => $this->_('street_suffix_code'),
                    'value'   => $this->street_suffix_code,
                    'type'    => 'select',
                    'options' => $streetTypes
                ]);
            ?>
            </fieldset>
            <fieldset><legend><?= $this->_('city'); ?></legend>
            <?php
                echo $h->field([
                    'name'    => 'city',
                    'id'      => 'city',
                    'label'   => $this->_('city'),
                    'value'   => $this->city,
                    'type'    => 'select',
                    'options' => $cities
                ]);
                echo $h->field([
                    'name'    => 'zip',
                    'id'      => 'zip',
                    'label'   => $this->_('zip'),
                    'value'   => $this->zip
                ]);
            ?>
                <button type="submit" class="search"><?= $this->_('search'); ?></button>
            </fieldset>
        </form>
    </details>

    <?php
        if (!$this->addresses) {
            if (isset($_GET['address'])) {
                echo sprintf($this->_('no_results', 'messages'), $this->_(['address', 'addresses', 10]));
            }
        }
        else {
            $this->_include('addresses/list.inc');
        }
    ?>
</section>
