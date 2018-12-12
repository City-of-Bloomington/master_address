<?php
/**
 * @copyright 2009-2018 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param Address $this->address
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
    <header><h1><?= $this->_('readdress'); ?></h1></header>
    <form method="post">
        <fieldset>
            <input name="id"          type="hidden" value="<?= $this->address_id; ?>" />
            <input name="location_id" type="hidden" value="<?= $this->location_id; ?>" />

            <?php $this->_include('addresses/partials/addressFields.inc'); ?>
        </fieldset>

        <fieldset>
            <?php $this->_include('addresses/partials/geoFields.inc'); ?>
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
