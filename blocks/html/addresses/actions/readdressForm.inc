<?php
/**
 * @copyright 2009-2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 *---------------------------------------------------------
 * Information to populate select dropdown options
 *---------------------------------------------------------
 * @param array  $this->cities           Cities to choose from
 * @param array  $this->jurisdictions    Jurisdictions to choose from
 * @param array  $this->quarterSections  Quarter Sections to choose from
 * @param array  $this->sections         Sections to choose from
 * @param array  $this->townships        Townships to choose from
 * @param array  $this->types            Address types to choose from
 * @param array  $this->zipCodes         Zip codes to choose from
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
 *---------------------------------------------------------
 * Location fields
 *---------------------------------------------------------
 * @param string   $this->address_id
 * @param string   $this->location_id    Readdressing preserves the location_id
 *
 * @param array    $this->locationTypes  Location types to choose from
 * @param string   $this->locationType_id
 * @param string   $this->mailable
 * @param string   $this->occupiable
 * @param string   $this->active
 * @param string   $this->retireSubunits  Whether to retire or readdress any subunits
 *
 *---------------------------------------------------------
 * Change log fields
 *---------------------------------------------------------
 * @param string $this->user_id
 * @param string $this->contact_id
 * @param string $this->contact_name  The displayable name for the chosen contact person
 * @param string $this->change_notes
 */
?>
<section>
    <header>
        <h1><?= $this->_('readdress'); ?></h1>
        <p><?= $this->_('addresses.readdress', 'messages'); ?></p>
    </header>
    <form method="post">
        <fieldset>
            <input name="id"            type="hidden" value="<?= $this->address_id;    ?>" />
            <input name="location_id"   type="hidden" value="<?= $this->location_id;   ?>" />
            <input name="state_plane_x" type="hidden" value="<?= $this->state_plane_x; ?>" />
            <input name="state_plane_y" type="hidden" value="<?= $this->state_plane_y; ?>" />
            <input name="latitude"      type="hidden" value="<?= $this->latitude;      ?>" />
            <input name="longitude"     type="hidden" value="<?= $this->longitude;     ?>" />
            <input name="usng"          type="hidden" value="<?= $this->usng;          ?>" />

            <?php $this->_include('addresses/partials/addressFields.inc'); ?>
        </fieldset>

        <fieldset>
            <legend><?= $this->_('location_info'); ?></legend>
            <?php $this->_include('locations/partials/locationFields.inc'); ?>
        </fieldset>

        <?php
            if ($this->subunits) {
                echo "<fieldset><legend>{$this->_(['subunit', 'subunits', 10])}</legend>";
                $h = $this->template->getHelper('field');
                $options = [
                    ['value'=>1, 'label'=>$this->_('retire'   )],
                    ['value'=>0, 'label'=>$this->_('readdress')]
                ];
                echo $h->field([
                    'name'     => 'retireSubunits',
                    'id'       => 'retireSubunits',
                    'type'     => 'select',
                    'value'    => $this->retireSubunits,
                    'options'  => $options
                ]);

                $this->_include('subunits/table.inc');

                echo "</fieldset>";
            }
        ?>

        <fieldset><legend><?= $this->_('changeLog'); ?></legend>
            <?php
                $this->_include('logs/changeLogFields.inc');

                $h = $this->template->getHelper('saveAndCancelButtons');
                echo $h->saveAndCancelButtons(parent::generateUri('addresses.view', ['id'=>$this->address_id]));
            ?>
        </fieldset>
    </form>
</section>
