<?php
/**
 * @copyright 2009-2018 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl-3.0.html GNU/AGPL, see LICENSE
 *
 * @param string $this->title
 * @param array  $this->towns        Towns to choose from in the select dropdown
 * @param array  $this->statuses     Statuses to choose from in the select dropdown
 * @param array  $this->types        Designation type options
 *
 * These are properties that the user previously submitted
 * @param int    $this->town_id      Street town ID
 * @param string $this->status       Street status
 * @param string $this->notes        Street notes
 *
 * @param int    $this->name_id      Street name ID
 * @param int    $this->type_id      Designation type ID
 * @param int    $this->rank
 * @param string $this->name         Display string for the current street name
 *
 * @param int    $this->user_id      User who is submitting the new street
 * @param int    $this->contact_id   Contact ID for the change log
 * @param Person $this->contact_name Contact person's name
 * @param string $this->change_notes Change log notes
 */
?>
<section>
    <header>
        <h1><?= $this->title; ?></h1>
    </header>
    <form method="post">
        <fieldset><legend><?= $this->_('street_info'); ?></legend>
        <?php
            $helper = $this->template->getHelper('field');

            $options = [];
            foreach ($this->statuses as $s) { $options[] = ['value'=>$s]; }
            echo $helper->field([
                'name'     => 'status',
                'id'       => 'status',
                'label'    => $this->_('status'),
                'type'     => 'select',
                'value'    => $this->status,
                'options'  => $options,
                'required' => true
            ]);

            $options = [['value'=>'']];
            foreach ($this->towns as $t) { $options[] = ['value'=>$t['id'], 'label'=>parent::escape($t['name'])]; }
            echo $helper->field([
                'name'     => 'town_id',
                'id'       => 'town_id',
                'label'    => $this->_('town'),
                'type'     => 'select',
                'value'    => $this->town_id,
                'options'  => $options
            ]);

            echo $helper->field([
                'name'  => 'notes',
                'id'    => 'notes',
                'label' => $this->_('street_notes'),
                'type'  => 'textarea',
                'value' => $this->notes
            ]);
        ?>
        </fieldset>
        <fieldset><legend><?= $this->_('designation'); ?></legend>
        <?php
            $this->_include('streets/actions/partials/designationFields.inc');
        ?>
        </fieldset>
        <fieldset><legend><?= $this->_('changeLog'); ?></legend>
        <?php
            $this->_include('logs/changeLogFields.inc');

            $helper = $this->template->getHelper('saveAndCancelButtons');
            echo $helper->saveAndCancelButtons(parent::generateUri('streets.index'));
        ?>
        </fieldset>
    </form>
</section>
