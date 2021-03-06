<?php
/**
 * @copyright 2009-2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param Township $this->township
 */
$fields = ['id', 'name', 'code', 'quarter_code'];
foreach ($fields as $f) {
    $$f = parent::escape($this->township->$f);
}
$title = $id ? $this->_('township_edit') : $this->_('township_add');
?>
<section>
    <header>
        <h1><?= $title; ?></h1>
    </header>
    <form method="post" action="<?= self::generateUri('townships.update'); ?>">
        <fieldset><legend><?= $this->_('township'); ?></legend>
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
                    'name'     => 'quarter_code',
                    'id'       => 'quarter_code',
                    'label'    => $this->_('quarter_code'),
                    'value'    => $quarter_code,
                    'attr'     => ['size'=>1, 'maxlength'=>1]
                ]);

                $h = $this->template->getHelper('saveAndCancelButtons');
                echo $h->saveAndCancelButtons(self::generateUri('townships.index'));
            ?>
        </fieldset>
    </form>
</section>
