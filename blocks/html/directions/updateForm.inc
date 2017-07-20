<?php
/**
 * @copyright 2009-2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param Direction $this->direction
 */
$fields = ['id', 'name', 'code'];
foreach ($fields as $f) {
    $get = 'get'.ucfirst($f);
    $$f  = parent::escape($this->direction->$get());
}
$title = $id ? $this->_('direction_edit') : $this->_('direction_add');
?>
<section>
    <header>
        <h1><?= $title; ?></h1>
    </header>
    <form method="post" action="<?= self::generateUri('directions.update'); ?>">
        <fieldset><legend><?= $this->_('direction'); ?></legend>
            <input name="id" type="hidden" value="<?= $id; ?>" />
            <?php
                $h = $this->template->getHelper('field');

                echo $h->field([
                    'name'     => 'code',
                    'id'       => 'code',
                    'label'    => $this->_('code'),
                    'required' => true,
                    'value'    => $code,
                    'attr'     => ['size'=>2, 'maxlength'=>2]
                ]);

                echo $h->field([
                    'name'     => 'name',
                    'id'       => 'name',
                    'label'    => $this->_('name'),
                    'required' => true,
                    'value'    => $name
                ]);


                $h = $this->template->getHelper('saveAndCancelButtons');
                echo $h->saveAndCancelButtons(self::generateUri('directions.index'));
            ?>
        </fieldset>
    </form>
</section>
