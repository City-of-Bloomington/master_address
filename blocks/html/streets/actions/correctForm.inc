<?php
/**
 * @copyright 2009-2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @param CorrectRequest $this->request
 */
use Application\Towns\TownsTable;

$id          = $this->request->street->getId();
$town_id     = parent::escape($this->request->streetInfo['town_id']);
$streetNotes = parent::escape($this->request->streetInfo['notes'  ]);
$contact_id  = parent::escape($this->request->changeLog['contact_id']);
$changeNotes = parent::escape($this->request->changeLog['notes'     ]);
?>
<section>
    <header>
        <h1><?= $this->_('correct'); ?></h1>
    </header>
    <form method="post" action="<?= $this->generateUri('streets.correct'); ?>">
        <fieldset><legend><?= $this->_('street_info'); ?></legend>
            <input name="id" type="hidden" value="<?php echo $id; ?>" />
            <?php
                $helper = $this->template->getHelper('field');

                $options = [['value'=>'']];
                $table = new TownsTable();
                $list  = $table->find();
                foreach ($list as $t) { $options[] = ['value'=>$t->getId(), 'label'=>parent::escape($t->getName())]; }
                echo $helper->field([
                    'name'    => 'street[town_id]',
                    'id'      => 'street-town_id',
                    'label'   => $this->_('town'),
                    'type'    => 'select',
                    'value'   => $town_id,
                    'options' => $options
                ]);

                echo $helper->field([
                    'name'  => 'street[notes]',
                    'id'    => 'street-notes',
                    'label' => $this->_('notes'),
                    'type'  => 'textarea',
                    'value' => $streetNotes
                ]);
            ?>
        </fieldset>
        <fieldset><legend><?= $this->_('changeLog'); ?></legend>
            <?php
                echo $helper->field([
                    'name'  => 'change[contact_id]',
                    'id'    => 'change-contact_id',
                    'label' => $this->_('contact'),
                    'value' => $contact_id
                ]);
                echo $helper->field([
                    'name'  => 'change[notes]',
                    'id'    => 'change-notes',
                    'label' => $this->_('notes'),
                    'type'  => 'textarea',
                    'value' => $changeNotes
                ]);

                $helper = $this->template->getHelper('saveAndCancelButtons');
                echo $helper->saveAndCancelButtons(
                    parent::generateUri('streets.view', ['id'=>$id])
                );
            ?>
        </fieldset>
    </form>
</section>
