<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param Subunit $this->subunit
 * @param int     $this->subunit_id;
 * @param string  $this->notes;
 * @param int     $this->contact_id    Change log contact person
 * @param string  $this->contact_name  Change log contact person
 * @param string  $this->change_notes
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

        <fieldset><legend><?= $this->_('changeLog'); ?></legend>
        <?php
            $this->_include('logs/changeLogFields.inc');

            $h = $this->template->getHelper('saveAndCancelButtons');
            echo $h->saveAndCancelButtons(parent::generateUri('subunits.view', ['id'=>$this->subunit_id]));
        ?>
        </fieldset>
    </form>
</section>
