<?php
/**
 * @copyright 2009-2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param VerifyRequest $this->request
 * @param Address       $this->address
 */
$id    = $this->request->address_id;
$notes = parent::escape($this->request->notes);
$user  = parent::escape(implode(' ', [$_SESSION['USER']->firstname, $_SESSION['USER']->lastname]));
?>
<section>
    <header><h1><?= $this->_('address_verify'); ?></h1></header>
    <div>
        <form method="post" action="<?= parent::generateUri('addresses.verify'); ?>">
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
                        parent::generateUri('addresses.view', ['id'=>$id])
                    );
                ?>
            </fieldset>
        </form>
        <table class="<?= $status; ?>">
        <?php
            $this->_include('addresses/partials/streetData.inc');
            $this->_include('addresses/partials/nonStreetData.inc');
        ?>
        </table>
    </div>
</section>
