<?php
/**
 * @copyright 2018 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param Town $this->town
 */
declare (strict_types=1);
$id = $this->town->id;
$name = parent::escape($this->town->name);
$code = parent::escape($this->town->code);

$title = $id ? $this->_('town_edit') : $this->_('town_add');
?>
<section>
    <header><h1><?= $title; ?></h1></header>
    <form method="post" action="<?= parent::generateUri('towns.update'); ?>">
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

                echo $helper->field([
                    'name'     => 'code',
                    'id'       => 'code',
                    'label'    => $this->_('code'),
                    'value'    => $code,
                    'required' => true
                ]);

                $helper = $this->template->getHelper('saveAndCancelButtons');
                echo $helper->saveAndCancelButtons(parent::generateUri('towns.index'));
            ?>
        </fieldset>
    </form>
</section>
