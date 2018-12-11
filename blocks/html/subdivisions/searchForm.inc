<?php
/**
 * @copyright 2017-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param array $this->subdivisions
 * @param array $this->options
 */
$fields = ['name', 'township_id', 'phase', 'status'];
foreach ($fields as $f) {
    $$f = !empty($_GET[$f]) ? parent::escape($_GET[$f]) : null;
}
?>
<section>
    <header>
        <h1><?= $this->_('subdivision_search'); ?></h1>
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
            foreach ($this->options['statuses'] as $s) { $options[] = ['value' => $s]; }
            echo $h->field([
                'name'    => 'status',
                'id'      => 'status',
                'label'   => $this->_('status'),
                'value'   => $status,
                'type'    => 'select',
                'options' => $options
            ]);

            $options = [['value'=>'']];
            foreach ($this->options['phases'] as $p) { $options[] = ['value' => $p]; }
            echo $h->field([
                'name'    => 'phase',
                'id'      => 'phase',
                'label'   => $this->_('phase'),
                'value'   => $phase,
                'type'    => 'select',
                'options' => $options
            ]);
        ?>
            <button type="submit" class="search"><?= $this->_('search'); ?></button>
        </fieldset>
    </form>
    <?php
        if (!count($this->subdivisions)) {
            if (isset($_GET['name'])) {
                echo sprintf($this->_('no_results', 'messages'), $this->_(['subdivision', 'subdivisions', 2]));
            }
        }
        else {
            $this->_include('subdivisions/list.inc');
        }
    ?>
</section>
