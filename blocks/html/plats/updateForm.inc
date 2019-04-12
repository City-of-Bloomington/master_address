<?php
/**
 * @copyright 2009-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param Plat   $this->plat
 * @param array  $this->options
 * @param string $this->title
 */
$fields = ['id', 'name', 'plat_type', 'cabinet', 'envelope', 'notes', 'township_id'];
foreach ($fields as $f) {
    $$f  = parent::escape($this->plat->$f);
}
?>
<section>
    <header><h1><?= $this->title; ?></h1></header>
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
                foreach ($this->options['townships'] as $t) { $options[] = ['value'=>$t['id'], 'label'=>parent::escape($t['name'])]; }
                echo $h->field([
                    'name'    => 'township_id',
                    'id'      => 'township_id',
                    'label'   => $this->_('township'),
                    'value'   => $township_id,
                    'type'    => 'select',
                    'options' => $options
                ]);

                echo $h->field([
                    'name'    => 'start_date',
                    'id'      => 'start_date',
                    'label'   => $this->_('startDate'),
                    'value'   => $this->plat->start_date,
                    'type'    => 'date'
                ]);

                echo $h->field([
                    'name'    => 'end_date',
                    'id'      => 'end_date',
                    'label'   => $this->_('endDate'),
                    'value'   => $this->plat->end_date,
                    'type'    => 'date'
                ]);

                $options = [['value'=>'']];
                foreach ($this->options['types'] as $t) { $options[] = ['value'=>$t]; }
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
                    'name'     => 'cabinet',
                    'id'       => 'cabinet',
                    'label'    => $this->_('cabinet'),
                    'value'    => $cabinet,
                    'type'     => 'select',
                    'options'  => $options
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
                    'label'    => $this->_('plat_notes'),
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
