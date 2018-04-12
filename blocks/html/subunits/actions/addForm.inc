<?php
/**
 * @copyright 2009-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param string $this->address_id;
 *
 * @param string $this->subunitType_id;
 * @param string $this->identifier;
 * @param string $this->notes;
 * @param string $this->state_plane_x;
 * @param string $this->state_plane_y;
 * @param string $this->latitude;
 * @param string $this->longitude;
 * @param string $this->usng;
 * @param string $this->subunitStatus;
 *
 * @param string $this->locationType_id;
 * @param string $this->mailable;
 * @param string $this->occupiable;
 * @param string $this->trash_day;
 * @param string $this->recycle_week;
 * @param string $this->locationStatus;
 *
 * @param string $this->user_id;
 * @param string $this->contact_id;
 * @param string $this->contact_name;
 * @param string $this->change_notes;
 *
 * @param array  $this->subunitTypes     Subunit types to populate dropdown
 * @param array  $this->locationTypes    Location types to populate dropdown
 * @param array  $this->statuses         Statuses to populate dropdown
 */
?>
<section>
    <header><h1><?= $this->_('add'); ?></h1></header>
    <form method="post">
        <fieldset><legend><?= $this->_('subunit'); ?></legend>
            <input name="address_id" type="hidden" value="<?= $this->address_id; ?>" />
            <?php
                $h = $this->template->getHelper('field');

                $options = [];
                foreach ($this->statuses as $s) { $options[] = ['value' => $s, 'label'=>$this->_($s)]; }
                echo $h->field([
                    'name'     => 'subunitStatus',
                    'id'       => 'subunitStatus',
                    'label'    => $this->_('status'),
                    'type'     => 'select',
                    'value'    => $this->subunitStatus,
                    'options'  => $options,
                    'required' => true
                ]);

                $options = [];
                foreach ($this->subunitTypes as $t) { $options[] = ['value'=>$t['id'], 'label'=>$t['code']]; }
                echo $h->field([
                    'name'     => 'subunitType_id',
                    'id'       => 'subunitType_id',
                    'label'    => $this->_('type'),
                    'type'     => 'select',
                    'value'    => $this->subunitType_id,
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
                    'name'     => 'state_plane_x',
                    'id'       => 'state_plane_x',
                    'label'    => $this->_('state_plane_x'),
                    'value'    => $this->state_plane_x,
                    'type'     => 'number'
                ]);

                echo $h->field([
                    'name'     => 'state_plane_y',
                    'id'       => 'state_plane_y',
                    'label'    => $this->_('state_plane_y'),
                    'value'    => $this->state_plane_y,
                    'type'     => 'number'
                ]);

                echo $h->field([
                    'name'     => 'latitude',
                    'id'       => 'latitude',
                    'label'    => $this->_('latitude'),
                    'value'    => $this->latitude,
                    'type'     => 'number',
                    'attr'     => [
                        'step' =>         '0.00000001',
                        'placeholder' => '39.00000000'
                    ]
                ]);

                echo $h->field([
                    'name'     => 'longitude',
                    'id'       => 'longitude',
                    'label'    => $this->_('longitude'),
                    'value'    => $this->longitude,
                    'type'     => 'number',
                    'attr'     => [
                        'step' =>          '0.00000001',
                        'placeholder' => '-86.00000000'
                    ]
                ]);

                echo $h->field([
                    'name'     => 'usng',
                    'id'       => 'usng',
                    'label'    => $this->_('usng'),
                    'value'    => $this->usng,
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
