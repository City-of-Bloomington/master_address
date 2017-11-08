<?php
/**
 * @copyright 2009-2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param Plat $this->plat
 */
use Application\Plats\Plat;
use Application\TableGateways\Townships;

$fields = ['id', 'name', 'type', 'cabinet', 'envelope', 'notes', 'township_id'];
foreach ($fields as $f) {
    $get = 'get'.ucfirst($f);
    $$f  = parent::escape($this->plat->$get());
}

$title = $id ? $this->_('plat_edit') : $this->_('plat_add');
?>
<section>
    <header><h1><?= $title; ?></h1></header>
    <form method="post" action="<?= parent::generateUri('plats.update'); ?>">
        <fieldset><legend><?= $this->_('plat_info'); ?></legend>
            <input name="id" type="hidden" value="<?= $id; ?>" />
            <?php
                $h = $this->template->getHelper('field');

                echo $h->field([
                    'name'     => 'name',
                    'id'       => 'name',
                    'label'    => $this->_('name'),
                    'value'    => $name,
                    'required' => true
                ]);

                $options = [['value'=>'']];
                $table   = new Townships();
                $list    = $table->find();
                foreach ($list as $t) { $options[] = ['value'=>$t->getId(), 'label'=>parent::escape($t->getName())]; }
                echo $h->field([
                    'name'    => 'township_id',
                    'id'      => 'township_id',
                    'label'   => $this->_('township'),
                    'value'   => $township_id,
                    'type'    => 'select',
                    'options' => $options
                ]);

                echo $h->field([
                    'name'    => 'startDate',
                    'id'      => 'startDate',
                    'label'   => $this->_('startDate'),
                    'value'   => $this->plat->getStartDate(),
                    'type'    => 'date'
                ]);

                echo $h->field([
                    'name'    => 'endDate',
                    'id'      => 'endDate',
                    'label'   => $this->_('endDate'),
                    'value'   => $this->plat->getEndDate(),
                    'type'    => 'date'
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

                echo $h->field([
                    'name'     => 'cabinet',
                    'id'       => 'cabinet',
                    'label'    => $this->_('cabinet'),
                    'value'    => $cabinet
                ]);
                echo $h->field([
                    'name'     => 'envelope',
                    'id'       => 'envelope',
                    'label'    => $this->_('envelope'),
                    'value'    => $envelope
                ]);

                echo $h->field([
                    'name'     => 'notes',
                    'id'       => 'notes',
                    'label'    => $this->_('notes'),
                    'value'    => $notes,
                    'type'     => 'textarea'
                ]);

                $h   = $this->template->getHelper('saveAndCancelButtons');
                $uri = $id
                    ? parent::generateUri('plats.view', ['id'=>$id])
                    : parent::generateUri('plats.index');
                echo $h->saveAndCancelButtons($uri);
            ?>
        </fieldset>
    </form>
</section>
