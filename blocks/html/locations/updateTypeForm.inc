<?php
/**
 * @copyright 2009-2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param LocationType $this->type
 */
$fields = ['id', 'name', 'code', 'description'];
foreach ($fields as $f) {
    $get = 'get'.ucfirst($f);
    $$f  = parent::escape($this->type->$get());
}
$title = $id ? $this->_('locationType_edit') : $this->_('locationType_add');
?>
<section>
    <header>
        <h1><?= $title; ?></h1>
    </header>
    <form method="post" action="<?= self::generateUri('locationTypes.update'); ?>">
        <fieldset><legend><?= $this->_('locationType'); ?></legend>
            <input name="id" type="hidden" value="<?= $id; ?>" />
            <?php
                $h = $this->template->getHelper('field');
                echo $h->field([
                    'name'     => 'name',
                    'id'       => 'name',
                    'label'    => $this->_('name'),
                    'required' => true,
                    'value'    => $name
                ]);

                echo $h->field([
                    'name'     => 'code',
                    'id'       => 'code',
                    'label'    => $this->_('code'),
                    'required' => true,
                    'value'    => $code,
                    'attr'     => ['size'=>2, 'maxlength'=>2]
                ]);
                echo $h->field([
                    'name'     => 'description',
                    'id'       => 'description',
                    'label'    => $this->_('description'),
                    'type'     => 'textarea',
                    'value'    => $description
                ]);

                $h = $this->template->getHelper('saveAndCancelButtons');
                echo $h->saveAndCancelButtons(self::generateUri('locationTypes.index'));
            ?>
        </fieldset>
    </form>
</section>
