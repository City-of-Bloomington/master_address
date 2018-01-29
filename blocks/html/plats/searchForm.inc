<?php
/**
 * @copyright 2009-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param array $this->plats
 * @param array $this->options
 */
$fields = ['name', 'township_id', 'plat_type', 'cabinet', 'envelope'];
foreach ($fields as $f) {
    $$f = !empty($_GET[$f]) ? parent::escape($_GET[$f]) : null;
}
?>
<section>
    <header>
        <h1><?= $this->_('plat_search'); ?></h1>
    </header>
    <form method="get">
        <fieldset>
        <?php
            $h = $this->template->getHelper('field');

            echo $h->field([
                'name'  => 'name',
                'id'    => 'name',
                'label' => $this->_('name'),
                'value' => $name
            ]);

            $options = [['value'=>'']];
            foreach ($this->options['townships'] as $t) {
                $options[] = ['value'=>$t['id'], 'label'=>parent::escape($t['name'])];
            }
            echo $h->field([
                'name'    => 'township_id',
                'id'      => 'township_id',
                'label'   => $this->_('township'),
                'value'   => $township_id,
                'type'    => 'select',
                'options' => $options
            ]);

            $options = [['value'=>'']];
            foreach ($this->options['plat_types'] as $t) { $options[] = ['value'=>$t]; }
            echo $h->field([
                'name'    => 'plat_type',
                'id'      => 'plat_type',
                'label'   => $this->_('type'),
                'value'   => $plat_type,
                'type'    => 'select',
                'options' => $options
            ]);

            $options = [['value'=>'']];
            foreach ($this->options['cabinets'] as $c) { $options[] = ['value'=>$c]; }
            echo $h->field([
                'name'    => 'cabinet',
                'id'      => 'cabinet',
                'label'   => $this->_('cabinet'),
                'value'   => $cabinet,
                'type'    => 'select',
                'options' => $options
            ]);

            echo $h->field(['name'=>'envelope', 'id'=>'envelope', 'label'=>$this->_('envelope'), 'value'=>$envelope]);

        ?>

            <button type="submit" class="search"><?= $this->_('search'); ?></button>
        </fieldset>
    </form>
    <?php
        if (!count($this->plats)) {
            if (isset($_GET['name'])) {
                echo sprintf($this->_('no_results', 'messages'), $this->_(['plat', 'plats', 2]));
            }
        }
        else {
            $this->_include('plats/list.inc');
        }
    ?>
</section>
