<?php
/**
 * @copyright 2009-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param string $this->address_id;
 *
 *---------------------------------------------------------
 * Information to populate select dropdown options
 *---------------------------------------------------------
 * @param array  $this->subunitTypes     Subunit types to populate dropdown
 * @param array  $this->locationTypes    Location types to populate dropdown
 * @param array  $this->statuses         Statuses to populate dropdown
 *
 *---------------------------------------------------------
 * Subunit table fields
 *---------------------------------------------------------
 * @param string $this->status;
 *
 * @param string $this->type_id;
 * @param string $this->identifier;
 * @param string $this->notes;
 * Geo fields
 * @param string $this->state_plane_x;
 * @param string $this->state_plane_y;
 * @param string $this->latitude;
 * @param string $this->longitude;
 * @param string $this->usng;
 *
 *---------------------------------------------------------
 * Location table fields
 *---------------------------------------------------------
 * @param string $this->locationType_id;
 * @param string $this->mailable;
 * @param string $this->occupiable;
 * @param string $this->trash_day;
 * @param string $this->recycle_week;
 * @param string $this->locationStatus;
 *
 *---------------------------------------------------------
 * Change log fields
 *---------------------------------------------------------
 * @param string $this->user_id;
 * @param string $this->contact_id;
 * @param string $this->contact_name;
 * @param string $this->change_notes;
 */
?>
<section>
    <header><h1><?= $this->_('subunit_add'); ?></h1></header>
    <form method="post">
        <fieldset><legend><?= $this->_('subunit'); ?></legend>
            <input name="address_id" type="hidden" value="<?= $this->address_id; ?>" />
            <?php
                $h = $this->template->getHelper('field');

                $options = [];
                foreach ($this->statuses as $s) { $options[] = ['value' => $s, 'label'=>$this->_($s)]; }
                echo $h->field([
                    'name'     => 'status',
                    'id'       => 'status',
                    'label'    => $this->_('status'),
                    'type'     => 'select',
                    'value'    => $this->status,
                    'options'  => $options,
                    'required' => true
                ]);

                $options = [];
                foreach ($this->subunitTypes as $t) { $options[] = ['value'=>$t['id'], 'label'=>$t['code']]; }
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

        <fieldset>
        <?php
            $this->_include('addresses/partials/geoFields.inc');
        ?>
        </fieldset>

        <fieldset><legend><?= $this->_('location'); ?></legend>
        <?php
            $this->_include('locations/partials/locationFields.inc');
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
