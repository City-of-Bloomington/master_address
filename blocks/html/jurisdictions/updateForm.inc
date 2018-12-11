<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param Jurisdiction $this->jurisdiction
 */
declare (strict_types=1);
$id = $this->jurisdiction->id;
$name = parent::escape($this->jurisdiction->name);

$title = $id ? $this->_('jurisdiction_edit') : $this->_('jurisdiction_add');
?>
<section>
    <header><h1><?= $title; ?></h1></header>
    <form method="post" action="<?= parent::generateUri('jurisdictions.update'); ?>">
        <fieldset>
            <input name="id" type="hidden" value="<?= $id; ?>" />
            <?php
                $helper = $this->template->getHelper('field');
                echo $helper->field([
                    'name'     => 'name',
                    'id'       => 'name',
                    'label'    => $this->_('name'),
                    'value'    => $name,
                    'required' => true
                ]);

                $helper = $this->template->getHelper('saveAndCancelButtons');
                echo $helper->saveAndCancelButtons(parent::generateUri('jurisdictions.index'));
            ?>
        </fieldset>
    </form>
</section>
