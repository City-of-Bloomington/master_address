<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param Type $this->type
 */
declare (strict_types=1);

$fields = ['id', 'name', 'description'];
foreach ($fields as $f) {
    $get = 'get'.ucfirst($f);
    $$f  = parent::escape($this->type->$get());
}

$title = $id ? $this->_('streetDesignationType_edit') : $this->_('streetDesignationType_add');
?>
<section>
    <header><h1><?= $title; ?></h1></header>
    <form method="post" action="<?= parent::generateUri('streetDesignationTypes.update'); ?>">
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
                'name'     => 'description',
                'id'       => 'description',
                'label'    => $this->_('description'),
                'value'    => $description,
                'required' => true
            ]);

            $helper = $this->template->getHelper('saveAndCancelButtons');
            echo $helper->saveAndCancelButtons(parent::generateUri('streetDesignationTypes.index'));
        ?>
        </fieldset>
    </form>
</section>
