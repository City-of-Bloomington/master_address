<?php
/**
 * @copyright 2018-2019 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl-3.0.txt GNU/AGPL, see LICENSE
 * @param int      $this->street_id
 * @param int      $this->designation_id
 * @param int      $this->type_id
 * @param DateTime $this->start_date
 * @param string   $this->contact_id   Change log contact person
 * @param string   $this->contact_name Change log contact person
 * @param string   $this->change_notes
 * @param array    $this->types         Designation type options
 */
declare (strict_types=1);
?>
<section>
    <header><h1><?= $this->_('designation_edit'); ?></h1></header>
    <form method="post">
        <fieldset>
            <input name="id" type="hidden" value="<?= $this->designation_id; ?>" />
            <?php
                $h = $this->template->getHelper('field');

                $options = [];
                foreach ($this->types as $t) { $options[] = ['value'=>$t['id'], 'label'=>parent::escape($t['name'])]; }
                echo $h->field([
                    'name'     => 'type_id',
                    'id'       => 'type_id',
                    'label'    => $this->_('type'),
                    'value'    => $this->type_id,
                    'type'     => 'select',
                    'options'  => $options,
                    'required' => true
                ]);

                echo $h->field([
                    'name'     => 'start_date',
                    'id'       => 'start_date',
                    'label'    => 'startDate',
                    'type'     => 'date',
                    'value'    => $this->start_date,
                    'required' => true
                ]);
            ?>

        </fieldset>
        <fieldset><legend><?= $this->_('changeLog'); ?></legend>
        <?php
            $this->_include('logs/changeLogFields.inc');

            $h = $this->template->getHelper('saveAndCancelButtons');
            echo $h->saveAndCancelButtons(parent::generateUri('streets.view', ['id'=>$this->street_id]));
        ?>
        </fieldset>
    </form>
</section>
