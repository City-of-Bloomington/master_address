<?php
/**
 * @copyright 2009-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @param VerifyRequest $this->request
 */
$id    = $this->request->street_id;
$notes = parent::escape($this->request->notes);
$user  = parent::escape(implode(' ', [$_SESSION['USER']->firstname, $_SESSION['USER']->lastname]));
?>
<section>
    <header>
        <h1><?= $this->_('street_verify'); ?></h1>
    </header>
    <form method="post" action="<?= parent::generateUri('streets.verify'); ?>">
        <fieldset>
            <input type="hidden" name="id" value="<?= $id; ?>" />
            <p><?= sprintf($this->_('verify_statement', 'messages'), $user); ?></p>
            <?php
                $helper = $this->template->getHelper('field');
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
