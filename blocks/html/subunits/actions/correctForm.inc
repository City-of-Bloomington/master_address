<?php
/**
 * @copyright 2009-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param string $this->subunit_id;
 * @param string $this->type_id;
 * @param string $this->identifier;
 * @param string $this->notes;
 * @param string $this->contact_id    Change log contact person
 * @param string $this->contact_name  Change log contact person
 * @param string $this->change_notes
 * @param array  $this->types         Subunit types to populate dropdown
 */
?>
<section>
    <header><h1><?= $this->_('correct'); ?></h1></header>
    <form method="post">
        <fieldset>
            <input name="id" type="hidden" value="<?= $this->subunit_id; ?>" />
            <?php
                $h = $this->template->getHelper('field');

                $options = [];
                foreach ($this->types as $t) { $options[] = ['value'=>$t['id'], 'label'=>$t['code']]; }
                echo $h->field([
                    'name'     => 'type_id',
                    'id'       => 'type_id',
                    'label'    => $this->_('type'),
                    'type'     => 'select',
                    'value'    => $this->type_id,
                    'options'  => $options,
                    'required' => true
                ]);

                echo $h->field([
                    'name'     => 'identifier',
                    'id'       => 'identifier',
                    'label'    => $this->_('identifier'),
                    'value'    => $this->identifier,
                    'required' => true
                ]);
                echo $h->field([
                    'name'     => 'notes',
                    'id'       => 'notes',
                    'label'    => $this->_('notes'),
                    'value'    => $this->notes,
                    'type'     => 'textarea'
                ]);
            ?>
        </fieldset>
        <fieldset><legend><?= $this->_('changeLog'); ?></legend>
            <?php
                $this->_include('logs/changeLogFields.inc');

                $h = $this->template->getHelper('saveAndCancelButtons');
                echo $h->saveAndCancelButtons(parent::generateUri('subunits.view', ['id'=>$this->subunit_id]));
            ?>
        </fieldset>
    </form>
</section>
