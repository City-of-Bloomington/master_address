<?php
/**
 * @copyright 2009-2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
use Application\Models\Plat;
use Application\Models\TableGateways\Townships;

$fields = ['name', 'township_id', 'type', 'cabinet', 'envelope'];
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
            $table   = new Townships();
            $list    = $table->find();
            foreach ($list as $t) {
                $options[] = ['value'=>$t->getId(), 'label'=>parent::escape($t->getName())];
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
            foreach (Plat::$types as $t) { $options[] = ['value'=>$t]; }
            echo $h->field([
                'name'    => 'type',
                'id'      => 'type',
                'label'   => $this->_('type'),
                'value'   => $type,
                'type'    => 'select',
                'options' => $options
            ]);

            $options = [['value'=>'']];
            $cabinets = Plat::getCabinets();
            foreach ($cabinets as $c) { $options[] = ['value'=>$c]; }
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
</section>
