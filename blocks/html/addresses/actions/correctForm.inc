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
use Application\Url;
use Domain\Addresses\Metadata as Address;
?>
<section>
    <header><h1><?= $this->_('correct'); ?></h1></header>
    <form method="post">
        <fieldset>
            <input name="id" type="hidden" value="<?= $this->address_id; ?>" />
            <?php
                $h = $this->template->getHelper('field');

                echo $h->field([
                    'name'  => 'street_number_prefix',
                    'id'    => 'street_number_prefix',
                    'label' => $this->_('street_number_prefix'),
                    'value' => $this->street_number_prefix
                ]);
                echo $h->field([
                    'name'     => 'street_number',
                    'id'       => 'street_number',
                    'label'    => $this->_('street_number'),
                    'value'    => $this->street_number,
                    'type'     => 'number',
                    'required' => true
                ]);
                echo $h->field([
                    'name'  => 'street_number_suffix',
                    'id'    => 'street_number_suffix',
                    'label' => $this->_('street_number_suffix'),
                    'value' => $this->street_number_suffix
                ]);

                echo $h->field([
                    'name'    => 'street_id',
                    'id'      => 'street_id',
                    'label'   => $this->_('street'),
                    'value'   => $this->street_id,
                    'display' => $this->street_name,
                    'type'    => 'chooser',
                    'chooser' => 'street'
                ]);

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
                echo $h->saveAndCancelButtons(parent::generateUri('addresses.view', ['id'=>$this->address_id]));
            ?>
        </fieldset>
    </form>
</section>
