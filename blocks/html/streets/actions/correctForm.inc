<?php
/**
 * @copyright 2009-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param string $this->street_id
 * @param string $this->town_id
 * @param string $this->notes
 * @param string $this->user_id
 * @param string $this->change_notes
 * @param array  $this->towns        Town options for the select dropdown
 */
?>
<section>
    <header>
        <h1><?= $this->_('correct'); ?></h1>
    </header>
    <form method="post">
        <fieldset><legend><?= $this->_('street_info'); ?></legend>
            <input name="id" type="hidden" value="<?= $this->street_id; ?>" />
            <?php
                $helper = $this->template->getHelper('field');

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
                    'label' => $this->_('notes'),
                    'type'  => 'textarea',
                    'value' => $this->notes
                ]);
            ?>
        </fieldset>
        <fieldset><legend><?= $this->_('changeLog'); ?></legend>
            <?php
                echo $helper->field([
                    'name'  => 'change_notes',
                    'id'    => 'change_notes',
                    'label' => $this->_('notes'),
                    'type'  => 'textarea',
                    'value' => $this->change_notes
                ]);

                $helper = $this->template->getHelper('saveAndCancelButtons');
                echo $helper->saveAndCancelButtons(
                    parent::generateUri('streets.view', ['id'=>$this->street_id])
                );
            ?>
        </fieldset>
    </form>
</section>
