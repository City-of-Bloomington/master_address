<?php
/**
 * @copyright 2009-2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param string $this->address_id;
 * @param string $this->street_id;
 * @param string $this->street_number_prefix;
 * @param string $this->street_number;
 * @param string $this->street_number_suffix;
 * @param string $this->street_name
 * @param string $this->zip;
 * @param string $this->zipplus4;
 * @param string $this->notes;
 * @param string $this->user_id;
 * @param string $this->contact_id   Change log contact person
 * @param string $this->contact_name Change log contact person
 * @param string $this->change_notes
 */
?>
<section>
    <header>
        <h1><?= $this->_('correct'); ?></h1>
        <p><?= $this->_('addresses.correct', 'messages'); ?></p>
    </header>
    <form method="post">
        <fieldset>
            <input name="id" type="hidden" value="<?= $this->address_id; ?>" />
            <?php
                $h = $this->template->getHelper('field');

                $this->_include('addresses/partials/streetFields.inc');

                echo $h->field([
                    'name'     => 'zip',
                    'id'       => 'zip',
                    'label'    => $this->_('zip'),
                    'value'    => $this->zip,
                    'type'     => 'number',
                    'required' => true
                ]);

                echo $h->field([
                    'name'     => 'zipplus4',
                    'id'       => 'zipplus4',
                    'label'    => $this->_('zipplus4'),
                    'value'    => $this->zipplus4,
                    'type'     => 'number'
                ]);

                echo $h->field([
                    'name'     => 'notes',
                    'id'       => 'notes',
                    'label'    => $this->_('address_notes'),
                    'value'    => $this->notes,
                    'type'     => 'textarea'
                ]);
            ?>
        </fieldset>

        <fieldset><legend><?= $this->_('changeLog'); ?></legend>
            <?php
                $this->_include('logs/changeLogFields.inc');

                $h = $this->template->getHelper('saveAndCancelButtons');
                echo $h->saveAndCancelButtons(parent::generateUri('addresses.view', ['id'=>$this->address_id]));
            ?>
        </fieldset>
    </form>
</section>
