<?php
/**
 * @copyright 2017-2018 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 *
 * @param Subunit $this->subunit      \Domain\Subunits\Entities\Subunit
 * @param string  $this->status
 * @param array   $this->statuses     Statuses to choose from in the select dropdown
 *
 * @param int     $this->user_id      User who is doing the data entry
 * @param int     $this->contact_id   Contact ID for the change log
 * @param Person  $this->contact_name Contact person's name
 * @param string  $this->change_notes Change log notes
 */
declare (strict_types=1);
?>
<section>
    <header><h1><?= $this->_('changeStatus'); ?></h1></header>
    <form method="post">
        <fieldset><legend><?= $this->subunit->__toString(); ?></legend>
            <input name="id" type="hidden" value="<?= $this->subunit->id; ?>" />
            <?php
                $helper = $this->template->getHelper('field');

                $options = [];
                foreach ($this->statuses as $s) { $options[] = ['value'=>$s]; }
                echo $helper->field([
                    'name'     => 'status',
                    'id'       => 'status',
                    'label'    => $this->_('status'),
                    'type'     => 'select',
                    'value'    => $this->status,
                    'options'  => $options,
                    'required' => true
                ]);
            ?>
        </fieldset>
        <fieldset><legend><?= $this->_('changeLog'); ?></legend>
            <?php
                $this->_include('logs/changeLogFields.inc');

                $helper = $this->template->getHelper('saveAndCancelButtons');
                echo $helper->saveAndCancelButtons(parent::generateUri('subunits.view', ['id'=>$this->subunit->id]));
            ?>
        </fieldset>
    </form>
</section>
