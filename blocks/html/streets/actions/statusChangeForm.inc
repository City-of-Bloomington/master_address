<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param StatusChangeRequest $this->request
 */
declare (strict_types=1);
use Application\Streets\Street;

$id = $this->request->street->getId();
$status = parent::escape($this->request->status);
$notes  = parent::escape($this->request->notes);
?>
<section>
    <header><h1><?= $this->_('street_changeStatus'); ?></h1></header>
    <form method="post" action="<?= parent::generateUri('streets.changeStatus'); ?>">
        <fieldset><legend><?= $this->_('street_info'); ?></legend>
            <input name="id" type="hidden" value="<?= $id; ?>" />
            <?php
                $helper = $this->template->getHelper('field');

                $options = [];
                foreach (Street::$statuses as $s) { $options[] = ['value'=>$s]; }
                echo $helper->field([
                    'name'    => 'status',
                    'id'      => 'status',
                    'label'   => $this->_('status'),
                    'type'    => 'select',
                    'value'   => $status,
                    'options' => $options
                ]);
            ?>
        </fieldset>
        <fieldset><legend><?= $this->_('changeLog'); ?></legend>
            <?php
                echo $helper->field([
                    'name'  => 'notes',
                    'id'    => 'notes',
                    'label' => $this->_('notes'),
                    'type'  => 'textarea',
                    'value' => $notes
                ]);

                $helper = $this->template->getHelper('saveAndCancelButtons');
                echo $helper->saveAndCancelButtons(
                    parent::generateUri('streets.view', ['id'=>$id])
                );
            ?>
        </fieldset>
    </form>
</section>
