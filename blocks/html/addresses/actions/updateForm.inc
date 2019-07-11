<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 *---------------------------------------------------------
 * Information to populate select dropdown options
 *---------------------------------------------------------
 * @param array  $this->jurisdictions
 * @param array  $this->quarterSections
 * @param array  $this->sections
 * @param array  $this->townships
 * @param array  $this->types            Address types
 * @param array  $this->locationTypes    Location types
 *---------------------------------------------------------
 * Address table fields
 *---------------------------------------------------------
 * @param int    $this->address_id
 * @param string $this->address2
 * @param string $this->address_type
 * @param int    $this->jurisdiction_id
 * @param int    $this->township_id
 * @param int    $this->subdivision_id
 * @param int    $this->plat_id
 * @param string $this->section
 * @param string $this->quarter_section
 * @param string $this->plat_lot_number
 * @param string $this->notes
 *---------------------------------------------------------
 * Location fields
 *---------------------------------------------------------
 * @param int    $this->locationType_id
 * @param bool   $this->mailable
 * @param bool   $this->occupiable
 * @param bool   $this->group_quarter
 *---------------------------------------------------------
 * Change log fields
 *---------------------------------------------------------
 * @param int    $this->user_id      User doing the data entry
 * @param int    $this->contact_id   Change log contact person
 * @param string $this->contact_name Change log contact person
 * @param string $this->change_notes
 */
declare (strict_types=1);
?>
<section>
    <header><h1><?= $this->_('update'); ?></header>
    <form method="post">
        <fieldset><legend><?= $this->_('address'); ?></legend>
            <input name="id" type="hidden" value="<?= $this->address_id; ?>" />
            <?php
                $h = $this->template->getHelper('field');

                $options = [['value'=>'']];
                foreach ($this->types as $c) { $options[] = ['value'=>$c]; }
                echo $h->field([
                    'name'     => 'address_type',
                    'id'       => 'address_type',
                    'label'    => $this->_('type'),
                    'value'    => $this->address_type,
                    'type'     => 'select',
                    'options'  => $options,
                    'required' => true
                ]);

                echo $h->field([
                    'name'  => 'address2',
                    'id'    => 'address2',
                    'label' => $this->_('address2'),
                    'value' => $this->address2
                ]);

                $this->_include('addresses/partials/descriptiveFields.inc');
            ?>
        </fieldset>

        <fieldset><legend><?= $this->_('location'); ?></legend>
            <?php
                $this->_include('locations/partials/locationFields.inc');
            ?>
        </fieldset>

        <fieldset><legemd><?= $this->_('changeLog'); ?></legend>
            <?php
                $this->_include('logs/changeLogFields.inc');

                $h = $this->template->getHelper('saveAndCancelButtons');
                echo $h->saveAndCancelButtons(parent::generateUri('addresses.view', ['id'=>$this->address_id]));
            ?>
        </fieldset>
    </form>
</section>
