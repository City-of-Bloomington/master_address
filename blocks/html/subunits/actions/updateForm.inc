<?php
/**
 * @copyright 2019-2020 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 *---------------------------------------------------------
 * Subunit table fields
 *---------------------------------------------------------
 * @param Subunit $this->subunit
 * @param int     $this->subunit_id
 * @param string  $this->notes
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
 * @param int     $this->contact_id    Change log contact person
 * @param string  $this->contact_name  Change log contact person
 * @param string  $this->change_notes
 *---------------------------------------------------------
 * Information to populate select dropdown options
 *---------------------------------------------------------
 * @param array  $this->locationTypes
 */
declare (strict_types=1);
?>
<section>
    <header><h1><?= $this->_('update'); ?></h1></header>
    <form method="post">
        <fieldset><legend><?= parent::escape($this->subunit); ?></legend>
            <input name="id" type="hidden" value="<?= $this->subunit_id; ?>" />
            <?php
                $h = $this->template->getHelper('field');
                echo $h->field([
                    'name'     => 'notes',
                    'id'       => 'notes',
                    'label'    => $this->_('subunit_notes'),
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
            echo $h->saveAndCancelButtons(parent::generateUri('subunits.view', ['id'=>$this->subunit_id]));
        ?>
        </fieldset>
    </form>
</section>
