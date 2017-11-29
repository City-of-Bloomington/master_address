<?php
/**
 * @copyright 2009-2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param UpdateRequest $this->request
 */
use Application\Streets\Designations\Designation;
foreach ($this->request as $k=>$v) {
    $$k = is_a($v, 'DateTime') ? $v : parent::escape($v);
}

$title = $id ? $this->_('streetDesignation_edit') : $this->_('streetDesignation_add');
?>
<section>
    <header><h1><?= $title ?></h1></header>
    <form method="post" action="<?= parent::generateUri('streetDesignations.update'); ?>">
        <fieldset><legend><?= $this->_('designation'); ?></lengend>
            <input name="id"        type="hidden" value="<?= $id;        ?>" />
            <input name="street_id" type="hidden" value="<?= $street_id; ?>" />
            <?php
                $helper = $this->template->getHelper('field');

                $options = [];
                foreach (Designation::$types as $t) { $options[] = ['value'=>$t['id'], 'label'=>$t['name']]; }
                echo $helper->field([
                    'name'     => 'type_id',
                    'id'       => 'type_id',
                    'label'    => $this->_('type'),
                    'type'     => 'select',
                    'value'    => $type_id,
                    'options'  => $options,
                    'required' => true
                ]);

                echo $helper->field([
                    'name'     => 'name_id',
                    'id'       => 'name_id',
                    'label'    => $this->_('name'),
                    'value'    => $name_id,
                    'required' => true
                ]);

                echo $helper->field([
                    'name'     => 'startDate',
                    'id'       => 'startDate',
                    'label'    => $this->_('startDate'),
                    'type'     => 'date',
                    'value'    => $startDate,
                    'required' => true
                ]);

                echo $helper->field([
                    'name'     => 'endDate',
                    'id'       => 'endDate',
                    'label'    => $this->_('endDate'),
                    'type'     => 'date',
                    'value'    => $endDate
                ]);

                echo $helper->field([
                    'name'     => 'rank',
                    'id'       => 'rank',
                    'label'    => $this->_('rank'),
                    'type'     => 'number',
                    'value'    => $rank
                ]);
            ?>
        </fieldset>

        <fieldset><legend><?= $this->_('changeLog'); ?></legend>
            <?php
                echo $helper->field([
                    'name'  => 'contact_id',
                    'id'    => 'contact_id',
                    'label' => $this->_('contact'),
                    'value' => $contact_id
                ]);
                echo $helper->field([
                    'name'  => 'notes',
                    'id'    => 'notes',
                    'label' => $this->_('notes'),
                    'type'  => 'textarea',
                    'value' => $notes
                ]);

                $helper = $this->template->getHelper('saveAndCancelButtons');
                echo $helper->saveAndCancelButtons(parent::generateUri('streets.view', ['id'=>$street_id]));
            ?>
        </fieldset>
    </form>
</section>
