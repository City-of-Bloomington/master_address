<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl-3.0.txt GNU/AGPL, see LICENSE
 *
 * @param string $this->cancel_url       The url that linked to this form
 *
 *---------------------------------------------------------
 * Information to populate select dropdown options
 *---------------------------------------------------------
 * @param array  $this->cities           Cities to choose from
 * @param array  $this->jurisdictions    Jurisdictions to choose from
 * @param array  $this->quarterSections  Quarter Sections to choose from
 * @param array  $this->sections         Sections to choose from
 * @param array  $this->statuses         Address statuses to choose from
 * @param array  $this->townships        Townships to choose from
 * @param array  $this->types            Address types to choose from
 * @param array  $this->zipCodes         Zip codes to choose from
 *
 *---------------------------------------------------------
 * Address table fields
 *---------------------------------------------------------
 * @param string $this->status
 * @param string $this->street_number_prefix
 * @param string $this->street_number
 * @param string $this->street_number_suffix
 * @param string $this->street_id
 * @param string $this->street_name           The displayable name for the chosen street_id
 * @param string $this->address2
 * @param string $this->address_type
 * @param string $this->jurisdiction_id
 * @param string $this->township_id
 * @param string $this->subdivision_id
 * @param string $this->plat_id
 * @param string $this->section
 * @param string $this->quarter_section
 * @param string $this->plat_lot_number
 * @param string $this->city
 * @param string $this->state
 * @param string $this->zip
 * @param string $this->zipplus4
 * @param string $this->notes
 * Geo Fields
 * @param string $this->state_plane_x
 * @param string $this->state_plane_y
 * @param string $this->latitude
 * @param string $this->longitude
 * @param string $this->usng
 *
 *---------------------------------------------------------
 * Location fields
 *---------------------------------------------------------
 * @param array    $this->locationTypes  Location types to choose from
 * @param array    $this->trashDays      Trash days to choose from
 * @param array    $this->recycleWeeks   Recycle weeks to choose from
 * @param Location $this->location       The currently chosen location object
 * If there is not a location_id, then the rest of the fields
 * need to be filled in
 * @param string   $this->location_id
 * @param string   $this->locationType_id
 * @param string   $this->mailable
 * @param string   $this->occupiable
 * @param string   $this->active
 * @param string   $this->trash_day
 * @param string   $this->recycle_week
 *
 *---------------------------------------------------------
 * Change log fields
 *---------------------------------------------------------
 * @param string $this->user_id
 * @param string $this->contact_id
 * @param string $this->contact_name  The displayable name for the chosen contact person
 * @param string $this->change_notes
 */
declare (strict_types=1);
use Application\Url;
?>
<section>
    <header><h1><?= $this->_('address_add'); ?></h1></header>

    <form method="post">
        <fieldset>
        <?php
            $h = $this->template->getHelper('field');

            #Status
            $options = [];
            foreach ($this->statuses as $c) { $options[] = ['value'=>$c]; }
            echo $h->field([
                'name'     => 'status',
                'id'       => 'status',
                'label'    => $this->_('status'),
                'value'    => $this->status,
                'type'     => 'select',
                'options'  => $options,
                'required' => true
            ]);

            #address_type
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
                'name'     => 'street_id',
                'id'       => 'street_id',
                'label'    => $this->_('street'),
                'type'     => 'chooser',
                'url'      => new Url(parent::generateUrl('streets.index')),
                'value'    => $this->street_id,
                'display'  => $this->street_name,
                'required' => true
            ]);

            echo $h->field([
                'name'  => 'address2',
                'id'    => 'address2',
                'label' => $this->_('address2'),
                'value' => $this->address2
            ]);

            $options = [['value'=>'']];
            foreach ($this->zipCodes as $z) { $options[] = ['value' => $z]; }
            echo $h->field([
                'name'     => 'zip',
                'id'       => 'zip',
                'label'    => $this->_('zip'),
                'value'    => $this->zip,
                'type'     => 'select',
                'options'  => $options,
                'required' => true
            ]);

            echo $h->field([
                'name'     => 'zipplus4',
                'id'       => 'zipplus4',
                'label'    => $this->_('zipplus4'),
                'value'    => $this->zipplus4,
                'type'     => 'number'
            ]);

            $options = [];
            foreach ($this->cities as $c) { $options[] = ['value'=>$c]; }
            echo $h->field([
                'name'     => 'city',
                'id'       => 'city',
                'label'    => $this->_('city'),
                'value'    => $this->city,
                'type'     => 'select',
                'options'  => $options,
                'required' => true
            ]);

            $options = [];
            foreach ($this->jurisdictions as $c) { $options[] = ['value'=>$c['id'], 'label'=>parent::escape($c['name'])]; }
            echo $h->field([
                'name'     => 'jurisdiction_id',
                'id'       => 'jurisdiction_id',
                'label'    => $this->_('jurisdiction'),
                'value'    => $this->jurisdiction_id,
                'type'     => 'select',
                'options'  => $options,
                'required' => true
            ]);

            #plat_id
            #plat_lot_number

            $options = [['value'=>'']];
            foreach ($this->townships as $c) { $options[] = ['value'=>$c['id'], 'label'=>parent::escape($c['name'])]; }
            echo $h->field([
                'name'     => 'township_id',
                'id'       => 'township_id',
                'label'    => $this->_('township'),
                'value'    => $this->township_id,
                'type'     => 'select',
                'options'  => $options,
                'required' => true
            ]);


            $options = [['value'=>'']];
            foreach ($this->sections as $c) { $options[] = ['value'=>$c]; }
            echo $h->field([
                'name'     => 'section',
                'id'       => 'section',
                'label'    => $this->_('section'),
                'value'    => $this->section,
                'type'     => 'select',
                'options'  => $options
            ]);

            $options = [['value'=>'']];
            foreach ($this->quarterSections as $c) { $options[] = ['value'=>$c]; }
            echo $h->field([
                'name'     => 'quarter_section',
                'id'       => 'quarter_section',
                'label'    => $this->_('quarter_section'),
                'value'    => $this->quarter_section,
                'type'     => 'select',
                'options'  => $options
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
        <fieldset><legend><?= $this->_('location_info'); ?></legend>
        <?php
            if ($this->location_id) {
                $this->disableButtons = true;
                $this->_include('locations/info.inc');
            }
            else { $this->_include('locations/partials/locationFields.inc'); }

        ?>
        </fieldset>
        <fieldset><legend><?= $this->_('changeLog'); ?></legend>
            <?php
                $this->_include('logs/changeLogFields.inc');

                $h = $this->template->getHelper('saveAndCancelButtons');
                echo $h->saveAndCancelButtons($this->cancel_url);
            ?>
        </fieldset>
    </form>
</section>
