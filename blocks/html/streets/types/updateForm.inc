<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param Type $this->type
 */
declare (strict_types=1);

$fields = ['id', 'code', 'name'];
foreach ($fields as $f) {
    $get = 'get'.ucfirst($f);
    $$f  = parent::escape($this->type->$get());
}

$title = $code ? $this->_('streetType_edit') : $this->_('streetType_add');
?>
<section>
    <header><h1><?= $title; ?></h1></header>
    <form method="post" action="<?= parent::generateUri('streetTypes.update'); ?>">
        <fieldset>
            <input name="id" type="hidden" value="<?= $id; ?>" />
        <?php
            $helper = $this->template->getHelper('field');
            echo $helper->field([
                'name'     => 'code',
                'id'       => 'code',
                'label'    => $this->_('code'),
                'value'    => $code,
                'required' => true
            ]);

            echo $helper->field([
                'name'     => 'name',
                'id'       => 'name',
                'label'    => $this->_('name'),
                'value'    => $name,
                'required' => true
            ]);

            $helper = $this->template->getHelper('saveAndCancelButtons');
            echo $helper->saveAndCancelButtons(parent::generateUri('streetTypes.index'));
        ?>
        </fieldset>
    </form>
</section>
