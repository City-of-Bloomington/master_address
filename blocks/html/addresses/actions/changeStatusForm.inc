<?php
/**
 * @copyright 2017-2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 *
 * @param int    $this->address_id
 * @param string $this->status
 * @param array  $this->statuses     Statuses to choose from in the select dropdown
 *
 * @param int    $this->user_id      User who is doing the data entry
 * @param int    $this->contact_id   Contact ID for the change log
 * @param Person $this->contact_name Contact person's name
 * @param string $this->change_notes Change log notes
 */
declare (strict_types=1);
?>
<section>
    <header>
        <h1><?= $this->_('changeStatus'); ?></h1>
        <p><?= $this->_('addresses.changeStatus', 'messages'); ?></p>
    </header>
    <form method="post">
        <fieldset><legend><?= $this->_('address_info'); ?></legend>
            <input name="id" type="hidden" value="<?= $this->address_id; ?>" />
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
            ?>
        </fieldset>
        <fieldset><legend><?= $this->_('changeLog'); ?></legend>
            <?php
                $this->_include('logs/changeLogFields.inc');

                $helper = $this->template->getHelper('saveAndCancelButtons');
                echo $helper->saveAndCancelButtons(parent::generateUri('addresses.view', ['id'=>$this->address_id]));
            ?>
        </fieldset>
    </form>
</section>
