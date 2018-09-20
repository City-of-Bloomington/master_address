<?php
/**
 * @copyright 2009-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param array  $this->towns          Town options for the select dropdown
 * @param array  $this->statuses       Status options for the select dropdown
 * @param string $this->street         The street name searched for
 * @param int    $this->town_id        The town_id searched for
 * @param string $this->status         The status searched for
 * @param array  $this->streets        Array of street results
 * @param array  $this->hidden         Hidden inputs to preserve
 * @param Url    $this->callback_url   Url to link person results to
 * @param string $this->callback_field Parameter to add to the callback_url
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
                    'value' => $this->street
                ]);

                $options = [['value'=>'']];
                foreach ($this->towns as $t) { $options[] = ['value'=>$t['id'], 'label'=>parent::escape($t['name'])]; }
                echo $h->field([
                    'name'     => 'town_id',
                    'id'       => 'town_id',
                    'label'    => $this->_('town'),
                    'type'     => 'select',
                    'value'    => $this->town_id,
                    'options'  => $options
                ]);

                $options = [['value'=>'']];
                foreach ($this->statuses as $s) { $options[] = ['value'=>$s]; }
                echo $h->field([
                    'name'     => 'status',
                    'id'       => 'status',
                    'label'    => $this->_('status'),
                    'type'     => 'select',
                    'value'    => $this->status,
                    'options'  => $options
                ]);

                foreach ($this->hidden as $k=>$v) {
                    echo "<input type=\"hidden\" name=\"$k\" value=\"$v\" />";
                }
            ?>
            <button type="submit" class="search"><?= $this->_('search'); ?></button>
        </fieldset>
    </form>
    <?php
        if (!$this->callback_url && parent::isAllowed('streets', 'add')) {
            $h = $this->template->getHelper('buttonLink');
            echo $h->buttonLink(
                parent::generateUri('streets.add'),
                $this->_('street_add'),
                'add'
            );
        }
        if (!$this->streets) {
            if ($this->street) {
                echo '<div>';
                echo sprintf($this->_('no_results', 'messages'), $this->_(['street', 'streets', 2]));
                echo '</div>';
            }
        }
        else {
            $this->_include('streets/list.inc');
        }
    ?>
</section>
