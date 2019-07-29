<?php
/**
 * @copyright 2009-2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param int      $this->street_id
 * @param int      $this->name_id
 * @param int      $this->type_id
 * @param DateTime $this->start_date
 * @param string   $this->contact_id   Change log contact person
 * @param string   $this->contact_name Change log contact person
 * @param string   $this->change_notes
 * @param string   $this->return_url
 * @param string   $this->name          Display string for the current street name
 * @param array    $this->types         Designation type options
 */
?>
<section>
    <header>
        <h1><?= $this->_('alias'); ?></h1>
        <p><?= $this->_('streets.alias', 'messages'); ?></p>
    </header>
    <form method="post">
        <fieldset>
            <input name="id" type="hidden" value="<?= $this->street_id; ?>" />
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
                    'name'     => 'name_id',
                    'id'       => 'name_id',
                    'label'    => $this->_('name'),
                    'value'    => $this->name_id,
                    'display'  => $this->name,
                    'type'     => 'chooser',
                    'chooser'  => 'streetName',
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
                echo $h->saveAndCancelButtons($this->return_url);
            ?>
        </fieldset>
    </form>
</section>
