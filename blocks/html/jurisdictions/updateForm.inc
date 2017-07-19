<?php
/**
 * @copyright 2009-2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param Jurisdiction $this->jurisdiction
 */
$id    = $this->jurisdiction->getId();
$name  = parent::escape($this->jurisdiction->getName());

$title = $id ? $this->_('jurisdiction_edit') : $this->_('jurisdiction_add');
?>
<section>
    <header>
        <h1><?= $title; ?></h1>
    </header>
    <form method="post" action="<?= self::generateUri('jurisdictions.update'); ?>">
        <fieldset><legend><?= $this->_('jurisdiction'); ?></legend>
            <input name="id" type="hidden" value="<?= $id; ?>" />
            <?php
                $h = $this->template->getHelper('field');
                echo $h->field([
                    'name'     => 'name',
                    'id'       => 'name',
                    'label '   => $this->_('name'),
                    'required' => true,
                    'value'    => $name
                ]);

                $h = $this->template->getHelper('saveAndCancelButtons');
                echo $h->saveAndCancelButtons(self::generateUri('jurisdictions.index'));
            ?>
        </fieldset>
    </form>
</section>
