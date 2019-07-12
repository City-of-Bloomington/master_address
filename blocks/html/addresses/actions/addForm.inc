<?php
/**
 * @copyright 2018-2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl-3.0.txt GNU/AGPL, see LICENSE
 *
 * @param string $this->cancel_url       The url that linked to this form
 *
 *---------------------------------------------------------
 * Information to populate select dropdown options
 *---------------------------------------------------------
 * @param array  $this->validActions     Action choices (Add, Assign, etc)
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
 *
 *---------------------------------------------------------
 * Location fields
 *---------------------------------------------------------
 * @param array    $this->locationTypes  Location types to choose from
 * @param Location $this->location       The currently chosen location object
 * If there is not a location_id, then the rest of the fields
 * need to be filled in
 * @param string   $this->location_id
 * @param string   $this->locationType_id
 * @param string   $this->mailable
 * @param string   $this->occupiable
 * @param string   $this->active
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
?>
<section>
    <header><h1><?= $this->_('address_add'); ?></h1></header>

    <form method="post">
        <fieldset>
        <?php
            $h = $this->template->getHelper('field');

            $options = [];
            foreach ($this->validActions as $a) { $options[] = ['value'=>$a, 'label'=>$this->_("action_help.address_$a", 'messages')]; }
            echo $h->field([
                'name'     => 'action',
                'id'       => 'action',
                'value'    => $this->action,
                'type'     => 'radio',
                'options'  => $options,
                'required' => true
            ]);

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
        ?>
        </fieldset>

        <fieldset><legend><?= $this->_('address'); ?></legend>
        <?php
            $this->_include('addresses/partials/addressFields.inc');
        ?>
        </fieldset>

        <fieldset><legend><?= $this->_('location'); ?></legend>
        <?php $this->_include('locations/partials/locationFields.inc'); ?>
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
